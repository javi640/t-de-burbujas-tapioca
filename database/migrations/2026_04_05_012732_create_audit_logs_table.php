<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('action', 50);        // 'login', 'open_shift', 'close_shift', 'void_sale'
            $table->string('model', 100)->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
 
            $table->index(['model', 'model_id']);
            $table->index('user_id');
            $table->index('created_at');
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};