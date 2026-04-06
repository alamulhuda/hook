<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Jasa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JasaController extends Controller
{
    public function index()
    {
        $jasa = Jasa::all();
        return Inertia::render('app/admin/master-data/jasa/Index', [
            'jasa' => $jasa,
            'user' => auth()->user(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jasa' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'estimasi_waktu_jam' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $jasa = Jasa::create($validated);
        return redirect()->back()->with('success', 'Jasa created successfully');
    }

    public function update(Request $request, Jasa $jasa)
    {
        $validated = $request->validate([
            'nama_jasa' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'estimasi_waktu_jam' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $jasa->update($validated);
        return redirect()->back()->with('success', 'Jasa updated successfully');
    }

    public function destroy(Jasa $jasa)
    {
        $jasa->delete();
        return redirect()->back()->with('success', 'Jasa deleted successfully');
    }
}
