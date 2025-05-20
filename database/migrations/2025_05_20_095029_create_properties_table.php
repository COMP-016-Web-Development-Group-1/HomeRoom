<?php

use App\Models\Landlord;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Landlord::class)->constrained()->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('address');
            $table->decimal('rent_amount', 11, 2)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
