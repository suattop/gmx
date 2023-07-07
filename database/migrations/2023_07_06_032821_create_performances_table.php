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
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('summary_id')->nullable();
            $table->foreign('summary_id')->references('id')->on('summaries')->onDelete('cascade');
            $table->timestamp('datetime')->nullable();
            $table->decimal('capital', 30, 18)->nullable();
            $table->decimal('balance', 30, 18)->nullable();
            $table->decimal('glp_amount_bal', 30, 18)->nullable();
            $table->decimal('glp_value_bal', 30, 18)->nullable();
            $table->decimal('glp_yield_bal', 30, 18)->nullable();
            $table->decimal('short_margin_bal', 30, 18)->nullable();
            $table->decimal('link_short_bal_value', 30, 18)->nullable(); 
            $table->decimal('uni_short_bal_value', 30, 18)->nullable(); 
            $table->decimal('btc_short_bal_value', 30, 18)->nullable(); 
            $table->decimal('eth_short_bal_value', 30, 18)->nullable(); 
            $table->decimal('link_short_price', 30, 18)->nullable(); 
            $table->decimal('uni_short_price', 30, 18)->nullable(); 
            $table->decimal('btc_short_price', 30, 18)->nullable(); 
            $table->decimal('eth_short_price', 30, 18)->nullable(); 
            $table->string('link_short_dex')->nullable(); 
            $table->string('uni_short_dex')->nullable(); 
            $table->string('btc_short_dex')->nullable(); 
            $table->string('eth_short_dex')->nullable(); 
            $table->decimal('link_short_amount', 30, 18)->nullable(); 
            $table->decimal('uni_short_amount', 30, 18)->nullable(); 
            $table->decimal('btc_short_amount', 30, 18)->nullable(); 
            $table->decimal('eth_short_amount', 30, 18)->nullable(); 
            $table->decimal('link_funding_bal', 30, 18)->nullable(); 
            $table->decimal('uni_funding_bal', 30, 18)->nullable(); 
            $table->decimal('btc_funding_bal', 30, 18)->nullable(); 
            $table->decimal('eth_funding_bal', 30, 18)->nullable(); 
            $table->integer('no_of_rebal')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performances');
    }
};
