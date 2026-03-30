<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained('orders');
            $table->foreignUuid('operative_id')->constrained('operatives');
            $table->foreignUuid('admin_id')->constrained('admins');
            $table->timestamp('assigned_at')->useCurrent();

            $table->unique(['order_id', 'operative_id']);
            $table->index('operative_id');
            $table->index('order_id');
            $table->index(['order_id', 'assigned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_assignments');
    }
};
