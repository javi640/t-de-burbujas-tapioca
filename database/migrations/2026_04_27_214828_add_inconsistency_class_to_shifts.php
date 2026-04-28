<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->enum('inconsistency_class', [
                'SIN_INCONSISTENCIA',
                'INCONSISTENCIA_LEVE',
                'INCONSISTENCIA_CRITICA'
            ])->nullable()->after('cash_difference');
        });
    }

    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn('inconsistency_class');
        });
    }
};