<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\Member;
use App\Models\Jasa;
use App\Models\Gudang;
use App\Models\AkunTransaksi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with(['brand', 'kategori'])->paginate(15);
        $brands = Brand::all();
        $kategoris = Kategori::all();

        return Inertia::render('app/admin/master-data/produk/Index', [
            'produks' => $produks->items(),
            'brands' => $brands,
            'kategoris' => $kategoris,
            'user' => auth()->user(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:produks',
            'brand_id' => 'nullable|exists:brands,id',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'deskripsi' => 'nullable|string',
            'harga_beli' => 'nullable|numeric',
            'harga_jual' => 'nullable|numeric',
            'stok' => 'nullable|integer|min:0',
        ]);

        Produk::create($validated);

        return redirect()->back()->with('success', 'Product created successfully');
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:produks,sku,' . $produk->id,
            'brand_id' => 'nullable|exists:brands,id',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'deskripsi' => 'nullable|string',
            'harga_beli' => 'nullable|numeric',
            'harga_jual' => 'nullable|numeric',
            'stok' => 'nullable|integer|min:0',
        ]);

        $produk->update($validated);

        return redirect()->back()->with('success', 'Product updated successfully');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->back()->with('success', 'Product deleted successfully');
    }
}
