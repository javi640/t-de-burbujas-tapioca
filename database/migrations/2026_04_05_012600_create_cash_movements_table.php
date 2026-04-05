<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cash_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shifts');
            $table->foreignId('created_by')->constrained('users');
            // INCOME = ingreso extra, EXPENSE = egreso/gasto
            $table->enum('movement_type', ['INCOME', 'EXPENSE']);
            $table->decimal('amount', 10, 2);
            $table->string('description', 255);
            $table->timestamps();
 
            $table->index('shift_id');
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('cash_movements');
    }
};