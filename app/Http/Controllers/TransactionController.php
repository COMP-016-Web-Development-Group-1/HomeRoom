<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = auth()->user()->role;

        return match ($role) {
            'landlord' => (function () {
                $pendingTransactions = Transaction::with(['tenant.room.property'])
                    ->where('status', 'pending')
                    ->orderByDesc('due_date')
                    ->get();

                $historyTransactions = Transaction::with(['tenant.room.property'])
                    ->where('status', '!=', 'pending')
                    ->orderByDesc('due_date')
                    ->get();

                return view('landlord.transaction.index', compact('pendingTransactions', 'historyTransactions'));
            })(),
            'tenant' => view('tenant.transaction.index'),
            default => abort(403),
        };
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
