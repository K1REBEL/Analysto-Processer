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
            $table->string('url');
            $table->string('current_seller');
            $table->string('last_seller')->nullable();
            $table->float('current_price');
            $table->float('last_price')->nullable();
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
