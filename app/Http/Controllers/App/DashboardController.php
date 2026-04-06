<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('app/dashboard', [
            'user' => auth()->user(),
        ]);
    }
}
