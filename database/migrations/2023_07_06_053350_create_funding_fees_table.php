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
        Schema::create('funding_fees', function (Blueprint $table) {
            $table->timestamp('datetime')->nullable();
            $table->string('dex');
            $table->string('token');
            $table->decimal('funding_fee', 30, 18)->nullable();
            $table->string('long_short');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funding_fees');
    }
};
