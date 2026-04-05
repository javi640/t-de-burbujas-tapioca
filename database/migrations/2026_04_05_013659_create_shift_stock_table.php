<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shift_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shifts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->unsignedInteger('initial_quantity');
            $table->unsignedInteger('sold_quantity')->default(0);
            // Cantidad actual = initial_quantity - sold_quantity
            $table->timestamps();
 
            // Un producto solo puede aparecer UNA VEZ por turno
            $table->unique(['shift_id', 'product_id']);
            $table->index('shift_id'); // Para listar stock del turno rápido
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('shift_stock');
    }
};