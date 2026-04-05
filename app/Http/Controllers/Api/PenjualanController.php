<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PenjualanResource;
use App\Models\Penjualan;
use App\Models\PenjualanItem;
use App\Models\PenjualanJasa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends BaseApiController
{
    /**
     * Store a newly created penjualan (POS transaction).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'id_member' => 'nullable|exists:md_members,id',
            'metode_bayar' => 'required|string|in:cash,card,transfer,ewallet',
            'akun_transaksi_id' => 'nullable|exists:akun_transaksis,id',
            'tunai_diterima' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string',
            'gudang_id' => 'nullable|exists:md_gudang,id',
            'items' => 'required_without:jasa_items|array',
            'items.*.id_pembelian_item' => 'required|exists:tb_pembelian_item,id_pembelian_item',
            'items.*.id_produk' => 'required|exists:md_produk,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga_jual' => 'required|numeric|min:0',
            'jasa_items' => 'required_without:items|array',
            'jasa_items.*.jasa_id' => 'required|exists:md_jasa,id',
            'jasa_items.*.qty' => 'required|integer|min:1',
            'jasa_items.*.harga' => 'required|numeric|min:0',
        ]);

        try {
            $penjualan = DB::transaction(function () use ($request) {
                $user = Auth::user();
                $karyawanId = $user->karyawan?->id;

                // 1. Create Penjualan record
                $penjualan = Penjualan::create([
                    'tanggal_penjualan' => now(),
                    'id_karyawan' => $karyawanId,
                    'id_member' => $request->input('id_member'),
                    'metode_bayar' => $request->input('metode_bayar'),
                    'akun_transaksi_id' => $request->input('akun_transaksi_id'),
                    'tunai_diterima' => $request->input('tunai_diterima', 0),
                    'catatan' => $request->input('catatan'),
                    'gudang_id' => $request->input('gudang_id'),
                    'sumber_transaksi' => 'pos',
                    'status_pembayaran' => 'belum_lunas',
                    'total' => 0,
                    'grand_total' => 0,
                ]);

                // 2. Create PenjualanItem records (model events handle stock mutation + HPP sync)
                if ($request->has('items')) {
                    foreach ($request->input('items') as $item) {
                        PenjualanItem::create([
                            'id_penjualan' => $penjualan->id_penjualan,
                            'id_produk' => $item['id_produk'],
                            'id_pembelian_item' => $item['id_pembelian_item'],
                            'qty' => $item['qty'],
                            'harga_jual' => $item['harga_jual'],
                        ]);
                    }
                }

                // 3. Create PenjualanJasa records
                if ($request->has('jasa_items')) {
                    foreach ($request->input('jasa_items') as $jasaItem) {
                        PenjualanJasa::create([
                            'id_penjualan' => $penjualan->id_penjualan,
                            'jasa_id' => $jasaItem['jasa_id'],
                            'qty' => $jasaItem['qty'],
                            'harga' => $jasaItem['harga'],
                        ]);
                    }
                }

                // 4. Recalculate totals and payment status
                $penjualan->recalculateTotals();
                $penjualan->recalculatePaymentStatus();

                // 5. Calculate kembalian
                $tunaiDiterima = (float) ($request->input('tunai_diterima', 0));
                $penjualan->refresh();
                $kembalian = max(0, $tunaiDiterima - (float) $penjualan->grand_total);
                $penjualan->forceFill(['kembalian' => $kembalian])->saveQuietly();

                return $penjualan;
            });

            // Reload with relations for response
            $penjualan->load(['member', 'karyawan', 'items.produk', 'jasaItems.jasa']);

            return $this->sendResponse(
                new PenjualanResource($penjualan),
                'Penjualan created successfully.'
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error.', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Failed to create penjualan.', ['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Penjualan::query()->with(['member', 'karyawan', 'items.produk']);

        // Only show sales for the currently logged in user based on their associated Karyawan record
        $user = Auth::user();
        if ($user && $user->karyawan) {
            $query->where('id_karyawan', $user->karyawan->id);
        }

        // Optional filtering by search term (no nota)
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('no_nota', 'like', "%{$search}%");
        }

        // Ordering by newest
        $query->orderBy('tanggal_penjualan', 'desc')
              ->orderBy('id_penjualan', 'desc');

        $penjualan = $query->paginate($request->input('per_page', 15));

        return $this->sendResponse(
            PenjualanResource::collection($penjualan)->response()->getData(true),
            'Penjualan retrieved successfully.'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $penjualan = Penjualan::with(['member', 'karyawan', 'items.produk'])->find($id);

        if (is_null($penjualan)) {
            return $this->sendError('Penjualan not found.');
        }

        return $this->sendResponse(new PenjualanResource($penjualan), 'Penjualan retrieved successfully.');
    }
}
