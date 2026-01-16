<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->string('barcode')->unique();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('unit')->nullable();
            $table->integer('initial_stock')->default(0);
            $table->integer('current_stock')->default(0);
            $table->date('expiry_date')->nullable();
            $table->string('manufacturer')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index('barcode');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};

