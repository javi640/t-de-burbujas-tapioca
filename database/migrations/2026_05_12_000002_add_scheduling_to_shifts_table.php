<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega los campos de control de asistencia al turno:
 *
 * opened_by          → Admin que creó el turno
 * scheduled_start    → Hora programada por el admin (ej. 09:00)
 * tolerance_minutes  → Minutos de tolerancia (ej. 15)
 * cajero_login_time  → Hora real en que el cajero inició sesión
 * attendance_status  → PENDIENTE / PUNTUAL / TARDANZA
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            // Quién abrió el turno (admin)
            $table->foreignId('opened_by')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('users');

            // Hora programada por el admin
            $table->timestamp('scheduled_start')
                  ->nullable()
                  ->after('start_time');

            // Minutos de tolerancia definidos por el admin
            $table->unsignedSmallInteger('tolerance_minutes')
                  ->default(0)
                  ->after('scheduled_start');

            // Hora real en que el cajero hizo login
            $table->timestamp('cajero_login_time')
                  ->nullable()
                  ->after('tolerance_minutes');

            // Estado de asistencia calculado al login del cajero
            $table->enum('attendance_status', ['PENDIENTE', 'PUNTUAL', 'TARDANZA'])
                  ->default('PENDIENTE')
                  ->after('cajero_login_time');
        });
    }

    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropForeign(['opened_by']);
            $table->dropColumn([
                'opened_by',
                'scheduled_start',
                'tolerance_minutes',
                'cajero_login_time',
                'attendance_status',
            ]);
        });
    }
};