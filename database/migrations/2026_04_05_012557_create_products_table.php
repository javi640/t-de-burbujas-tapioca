<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('name', 100);
            // DECIMAL(10,2) correcto para dinero en MySQL
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
 
            $table->index(['branch_id', 'is_active']); // Para listar productos activos de una sucursal
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};