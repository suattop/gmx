<?php

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
        Schema::create('glp_compositions', function (Blueprint $table) {
            $table->timestamp('datetime')->nullable();
            $table->string('token');
            $table->decimal('amount', 30, 18)->nullable();
            $table->decimal('cumulative_amount', 30, 18)->nullable();
            $table->decimal('value', 30, 18)->nullable();
            $table->decimal('cumulative_value', 30, 18)->nullable();
            $table->decimal('price', 30, 18)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('glp_compositions');
    }
};
