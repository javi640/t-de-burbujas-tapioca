<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            // ENUM en MySQL: válido para valores fijos y limitados
            $table->enum('status', ['OPEN', 'CLOSED'])->default('OPEN');
            $table->timestamp('start_time')->useCurrent();
            $table->timestamp('end_time')->nullable();
            $table->decimal('initial_cash', 10, 2)->default(0.00);
            // Lo que el cajero declara al cerrar
            $table->decimal('reported_cash', 10, 2)->nullable();
            // Diferencia calculada al cierre (esperado - declarado)
            $table->decimal('cash_difference', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
 
            // CRÍTICO: Índice compuesto para validar "¿tiene turno abierto?"
            // Esta consulta se ejecuta en CADA login de cajero
            $table->index(['user_id', 'status']);
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};