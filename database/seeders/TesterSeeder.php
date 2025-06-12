<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $transaction = Transaction::create([
        //     'tenant_id' => 1,
        //     'bill_id' => 1,
        //     'amount' => 5000,
        //     'payment_method' => 'gcash',
        //     'payment_date' => now(),
        // ]);

        $transaction = Transaction::find(1);

        $transaction->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        $transaction->bill->update([
            'status' => 'paid',
        ]);
    }
}
