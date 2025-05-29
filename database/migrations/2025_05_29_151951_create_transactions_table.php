<?php

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Tenant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tenant::class)->constrained()->onDelete('cascade');
            $table->enum('type', array_column(TransactionType::cases(), 'value'));
            $table->decimal('amount', 10, 2);
            $table->date('due_date')->nullable();
            $table->enum('status', array_column(TransactionStatus::cases(), 'value'))
                ->default(TransactionStatus::PENDING->value);
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
