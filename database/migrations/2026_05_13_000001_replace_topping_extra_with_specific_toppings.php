<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // No se puede eliminar Topping Extra porque tiene ventas registradas.
        // Se desactiva y renombra para que no aparezca en el sistema,
        // pero el historial de ventas queda intacto.
        DB::table('products')
            ->where('name', 'Topping Extra')
            ->update([
                'is_active'  => false,
                'name'       => 'Topping Extra (descontinuado)',
                'updated_at' => now(),
            ]);

        // Insertar los 8 toppings específicos
        $now = now();
        DB::table('products')->insert([
            ['branch_id' => 1, 'name' => 'Tapioca Pearls',  'price' => 5.00, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['branch_id' => 1, 'name' => 'Nata de Coco',    'price' => 5.00, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['branch_id' => 1, 'name' => 'Pudding Jelly',   'price' => 5.00, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['branch_id' => 1, 'name' => 'Oreo Crumbs',     'price' => 5.00, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['branch_id' => 1, 'name' => 'Mango Boba',      'price' => 5.00, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['branch_id' => 1, 'name' => 'Strawberry Boba', 'price' => 5.00, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['branch_id' => 1, 'name' => 'Passion Boba',    'price' => 5.00, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['branch_id' => 1, 'name' => 'Coffee Jelly',    'price' => 5.00, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        // Reactivar el topping original
        DB::table('products')
            ->where('name', 'Topping Extra (descontinuado)')
            ->update([
                'is_active'  => true,
                'name'       => 'Topping Extra',
                'updated_at' => now(),
            ]);

        // Eliminar los toppings específicos (estos sí se pueden borrar
        // siempre que no tengan ventas asociadas)
        $toppings = ['Tapioca Pearls', 'Nata de Coco', 'Pudding Jelly', 'Oreo Crumbs',
                     'Mango Boba', 'Strawberry Boba', 'Passion Boba', 'Coffee Jelly'];

        DB::table('products')->whereIn('name', $toppings)->delete();
    }
};