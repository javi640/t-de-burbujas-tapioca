<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Sucursal principal
        $branch = Branch::create([
            'name' => 'Panda Naicha - Principal',
            'location' => 'La Paz, Bolivia',
        ]);

        // Roles
        $adminRole = Role::create(['name' => 'Administrador', 'slug' => 'admin']);
        $cajeroRole = Role::create(['name' => 'Cajero',        'slug' => 'cajero']);

        // Admin
        User::create([
            'branch_id' => $branch->id,
            'role_id' => $adminRole->id,
            'name' => 'Administrador',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        // Cajero de prueba
        User::create([
            'branch_id' => $branch->id,
            'role_id' => $cajeroRole->id,
            'name' => 'Cajero Prueba',
            'username' => 'cajero1',
            'password' => Hash::make('cajero123'),
        ]);

        // ── Bebidas ──────────────────────────────────────────────
        $bebidas = [
            ['name' => 'Naicha Original',  'price' => 15.00, 'category' => 'BEBIDA'],
            ['name' => 'Naicha Frutilla',  'price' => 17.00, 'category' => 'BEBIDA'],
            ['name' => 'Naicha Mango',     'price' => 17.00, 'category' => 'BEBIDA'],
            ['name' => 'Naicha Maracuyá',  'price' => 17.00, 'category' => 'BEBIDA'],
            ['name' => 'Naicha Matcha',    'price' => 20.00, 'category' => 'BEBIDA'],
            ['name' => 'Taro Latte',       'price' => 22.00, 'category' => 'BEBIDA'],
        ];

        foreach ($bebidas as $product) {
            Product::create([
                'branch_id' => $branch->id,
                'name' => $product['name'],
                'price' => $product['price'],
                'category' => $product['category'],
                'is_active' => true,
            ]);
        }

        // ── Toppings / Extras ─────────────────────────────────────
        $toppings = [
            ['name' => 'Tapioca Pearls',  'price' => 5.00, 'category' => 'TOPPING'],
            ['name' => 'Nata de Coco',    'price' => 5.00, 'category' => 'TOPPING'],
            ['name' => 'Pudding Jelly',   'price' => 5.00, 'category' => 'TOPPING'],
            ['name' => 'Oreo Crumbs',     'price' => 5.00, 'category' => 'TOPPING'],
            ['name' => 'Mango Boba',      'price' => 5.00, 'category' => 'TOPPING'],
            ['name' => 'Strawberry Boba', 'price' => 5.00, 'category' => 'TOPPING'],
            ['name' => 'Passion Boba',    'price' => 5.00, 'category' => 'TOPPING'],
            ['name' => 'Coffee Jelly',    'price' => 5.00, 'category' => 'TOPPING'],
        ];

        foreach ($toppings as $topping) {
            Product::create([
                'branch_id' => $branch->id,
                'name' => $topping['name'],
                'price' => $topping['price'],
                'category' => $topping['category'],
                'is_active' => true,
            ]);
        }
    }
}
