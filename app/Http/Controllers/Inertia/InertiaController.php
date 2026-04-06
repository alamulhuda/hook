<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class InertiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function render(string $component, array $data = [])
    {
        return Inertia::render($component, $data);
    }
}
