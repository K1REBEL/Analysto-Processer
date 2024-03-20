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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->required();
            $table->string('email')->required();
            $table->string('password');
            $table->boolean('password_set')->default(false);
            $table->string('niche');
            $table->string('region');
            $table->string('phone');
            $table->boolean('is_active')->default(true);
            $table->integer('latest_avg_revenue');
            $table->integer('subscription_amount');
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
