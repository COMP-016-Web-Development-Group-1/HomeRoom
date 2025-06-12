<?php

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Bill;
use App\Models\Tenant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tenant::class)->constrained()->onDelete('cascade'); // Tenant
            $table->foreignIdFor(Bill::class)->unique()->constrained()->onDelete('cascade'); // One-to-one
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->string('payment_method')->nullable(); // optional (e.g. 'gcash', 'bank')
            $table->string('proof_path')->nullable();     // file path to uploaded receipt
            $table->date('payment_date');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
