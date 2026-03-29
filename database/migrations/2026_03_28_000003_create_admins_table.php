<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->unique()->constrained('users');
            $table->string('document_type', 10);
            $table->string('document', 30)->unique();
            $table->string('name', 150);
            $table->string('email', 150)->unique()->nullable();
            $table->string('phone', 20)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
