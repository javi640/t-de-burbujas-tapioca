<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('category', ['BEBIDA', 'TOPPING'])
                ->default('BEBIDA')
                ->after('price');
        });

        $toppingKeywords = ['Pearls', 'Boba', 'Jelly', 'Crumbs', 'Nata', 'Pudding'];

        DB::table('products')
            ->where(function ($query) use ($toppingKeywords) {
                foreach ($toppingKeywords as $keyword) {
                    $query->orWhere('name', 'like', "%{$keyword}%");
                }
            })
            ->update(['category' => 'TOPPING']);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
