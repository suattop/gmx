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
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();
            $table->timestamp('start_datetime')->nullable();
            $table->timestamp('end_datetime')->nullable();
            $table->string('method')->nullable();
            $table->integer('rebalance_hours')->nullable();
            $table->decimal('glp_weight', 30, 18)->nullable();
            $table->decimal('short_percentage', 30, 18)->nullable();
            $table->decimal('min_apr', 30, 18)->nullable();
            $table->decimal('capital', 30, 18)->nullable();
            $table->decimal('balance', 30, 18)->nullable();
            $table->decimal('glp_amount', 30, 18)->nullable();
            $table->decimal('glp_bal_bf', 30, 18)->nullable();
            $table->decimal('glp_bal_cf', 30, 18)->nullable();
            $table->decimal('short_margin_bf', 30, 18)->nullable();
            $table->decimal('short_margin_cf', 30, 18)->nullable();
            $table->decimal('short_funding_fees', 30, 18)->nullable();
            $table->decimal('apr', 30, 18)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summaries');
    }
};
