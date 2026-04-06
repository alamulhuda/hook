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
        return Inertia::render('app/admin/master-data/akun-transaksi/Index', [
            'akunTransaksi' => $akunTransaksi,
            'user' => auth()->user(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_akun' => 'required|string|max:255',
            'nama_bank' => 'nullable|string|max:255',
            'nama_rekening' => 'nullable|string|max:255',
            'no_rekening' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'catatan' => 'nullable|string',
        ]);

        $lastKode = AkunTransaksi::query()
            ->whereNotNull('kode_akun')
            ->orderByDesc('kode_akun')
            ->value('kode_akun');

        $nextNumber = 1;
        if ($lastKode && preg_match('/(\d+)$/', $lastKode, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }
        $validated['kode_akun'] = 'AKN' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

        AkunTransaksi::create($validated);
        return redirect()->back()->with('success', 'Akun Transaksi created successfully');
    }

    public function update(Request $request, AkunTransaksi $akunTransaksi)
    {
        $validated = $request->validate([
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
