<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;

        return match ($role) {
            'landlord' => view('dashboard'),
            'tenant' => view('dashboard'),
            default => abort(403),
        };
    }
}
