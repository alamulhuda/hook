<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\AkunTransaksi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class AkunTransaksiController extends Controller
{
    public function index()
    {
        $akunTransaksi = AkunTransaksi::all();
        return Inertia::render('modules/akunting/pages/master-data/akun-transaksi/Index', [
            'akunTransaksi' => $akunTransaksi,
            'user' => auth()->user(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_akun' => 'nullable|string|max:255|unique:akun_transaksis,kode_akun',
            'nama_akun' => 'required|string|max:255',
            'nama_bank' => 'nullable|string|max:255',
            'nama_rekening' => 'nullable|string|max:255',
            'no_rekening' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'catatan' => 'nullable|string',
        ]);

        if (empty($validated['kode_akun'])) {
            $lastKode = AkunTransaksi::query()
                ->where('kode_akun', 'like', '111%')
                ->orderByDesc('kode_akun')
                ->value('kode_akun');

            $nextNumber = 1;
            if ($lastKode && preg_match('/(\d+)$/', $lastKode, $matches)) {
                $nextNumber = ((int) substr($matches[1], -3)) + 1;
            }
            $validated['kode_akun'] = '111' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
        }

        AkunTransaksi::create($validated);
        return redirect()->back()->with('success', 'Akun Transaksi created successfully');
    }

    public function update(Request $request, AkunTransaksi $akunTransaksi)
    {
        $validated = $request->validate([
            'kode_akun' => 'required|string|max:255|unique:akun_transaksis,kode_akun,' . $akunTransaksi->id,
            'nama_akun' => 'required|string|max:255',
            'nama_bank' => 'nullable|string|max:255',
            'nama_rekening' => 'nullable|string|max:255',
            'no_rekening' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'catatan' => 'nullable|string',
        ]);

        $akunTransaksi->update($validated);
        return redirect()->back()->with('success', 'Akun Transaksi updated successfully');
    }

    public function destroy(AkunTransaksi $akunTransaksi)
    {
        $akunTransaksi->delete();
        return redirect()->back()->with('success', 'Akun Transaksi deleted successfully');
    }
}
