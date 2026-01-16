<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('threshold_percentage', 5, 2)->default(10.00);
            $table->decimal('current_stock_percentage', 5, 2);
            $table->dateTime('alert_sent_at')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
            
            $table->index('medicine_id');
            $table->index('user_id');
            $table->index('is_resolved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_alerts');
    }
};

