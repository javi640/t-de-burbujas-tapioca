<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shift_stock', function (Blueprint $table) {
            $table->unique(['shift_id', 'product_id'], 'idx_shift_stock_unique');
        });

        Schema::table('sale_details', function (Blueprint $table) {
            $table->index(['sale_id', 'product_id'], 'idx_sale_details_sale_product');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'idx_audit_user_date');
            $table->index(['action', 'created_at'],  'idx_audit_action_date');
        });
    }

    public function down(): void
    {
        Schema::table('shift_stock', function (Blueprint $table) {
            $table->dropUnique('idx_shift_stock_unique');
        });

        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropIndex('idx_sale_details_sale_product');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('idx_audit_user_date');
            $table->dropIndex('idx_audit_action_date');
        });
    }
};