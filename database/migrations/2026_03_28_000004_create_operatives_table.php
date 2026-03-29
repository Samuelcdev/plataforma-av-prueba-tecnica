<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operatives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('document_type', 10);
            $table->string('document', 30)->unique();
            $table->string('name', 150);
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operatives');
    }
};
