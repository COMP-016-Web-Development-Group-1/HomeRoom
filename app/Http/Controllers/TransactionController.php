<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = auth()->user()->role;

        return match ($role) {
            'landlord' => view('landlord.transaction.index', [
                'transactions' => \App\Models\Transaction::with(['tenant.room.property'])->orderByDesc('due_date')->get()
            ]),
            'tenant' => view('tenant.transaction.index'),
            default => abort(403),
        };
    }

    /**
     * Landlord transaction listing, eager-load tenant and property.
     */
    protected function landlordIndex()
    {
        // Eager load tenant and property relationship
        $transactions = Transaction::with(['tenant.property'])
            ->orderByDesc('due_date')
            ->get();

        return view('landlord.transaction.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
