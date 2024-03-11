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
        Schema::create('tracking_results', function (Blueprint $table) {
            $table->id();
            $table->string('platform');
            $table->string('date');
            $table->string('time');
            $table->string('brand');
            $table->string('category');
            $table->string('identifier');
            $table->string('sku');
            $table->text('title');
            $table->string('current_seller');
            $table->string('last_seller');
            $table->decimal('current_price', 10, 2);
            $table->decimal('last_price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_results');
    }
};
