<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('agents')->get();
        return Inertia::render('app/admin/master-data/supplier/Index', [
            'suppliers' => $suppliers,
            'user' => auth()->user(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'provinsi' => 'nullable|string|max:100',
            'kota' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
        ]);

        Supplier::create($validated);
        return redirect()->back()->with('success', 'Supplier created successfully');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'provinsi' => 'nullable|string|max:100',
            'kota' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
        ]);

        $supplier->update($validated);
        return redirect()->back()->with('success', 'Supplier updated successfully');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->back()->with('success', 'Supplier deleted successfully');
    }
}
