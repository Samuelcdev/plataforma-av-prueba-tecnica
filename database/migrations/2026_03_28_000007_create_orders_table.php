<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('hotel_id')->constrained('hotels');
            $table->string('name', 150);
            $table->string('service_type', 100);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->index(['start_date', 'end_date']);
            $table->index('hotel_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
