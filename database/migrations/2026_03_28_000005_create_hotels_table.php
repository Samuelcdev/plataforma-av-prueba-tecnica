<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->unique()->constrained('users');
            $table->string('nit', 20)->unique();
            $table->string('document_type', 10);
            $table->string('name', 150);
            $table->string('phone', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('address');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
