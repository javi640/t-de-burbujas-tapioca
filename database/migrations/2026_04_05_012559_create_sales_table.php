<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shifts');
            $table->decimal('total_amount', 10, 2);
            // CASH = efectivo, QR = transferencia QR
            $table->enum('payment_method', ['CASH', 'QR']);
            $table->enum('status', ['COMPLETED', 'VOIDED'])->default('COMPLETED');
            $table->timestamp('sale_time')->useCurrent();
            // Usuario que anuló (si aplica)
            $table->foreignId('voided_by')->nullable()->constrained('users');
            $table->text('void_reason')->nullable();
            $table->timestamps();
 
            // Índices para reportes y dashboard
            $table->index('shift_id');
            $table->index('sale_time');
            $table->index(['shift_id', 'payment_method']); // Conciliación CASH vs QR
            $table->index(['shift_id', 'status']);          // Solo ventas COMPLETED
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};