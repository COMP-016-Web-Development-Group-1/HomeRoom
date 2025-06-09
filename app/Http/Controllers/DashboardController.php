<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;

        return match ($role) {
            'landlord' => view('landlord.dashboard'),
            'tenant' => view('tenant.dashboard'),
            default => abort(403),
        };
    }
}
