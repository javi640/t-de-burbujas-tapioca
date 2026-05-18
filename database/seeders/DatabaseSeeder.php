<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Sucursal principal
        $branch = Branch::create([
            'name'     => 'Panda Naicha - Principal',
            'location' => 'La Paz, Bolivia',
        ]);

        // Roles
        $adminRole  = Role::create(['name' => 'Administrador', 'slug' => 'admin']);
        $cajeroRole = Role::create(['name' => 'Cajero',        'slug' => 'cajero']);

        // Admin
        User::create([
            'branch_id' => $branch->id,
            'role_id'   => $adminRole->id,
            'name'      => 'Administrador',
            'username'  => 'admin',
            'password'  => Hash::make('admin123'),
        ]);

        // Cajero de prueba
        User::create([
            'branch_id' => $branch->id,
            'role_id'   => $cajeroRole->id,
            'name'      => 'Cajero Prueba',
            'username'  => 'cajero1',
            'password'  => Hash::make('cajero123'),
        ]);

        // ── Bebidas ──────────────────────────────────────────────
        $bebidas = [
            ['name' => 'Naicha Original',  'price' => 15.00],
            ['name' => 'Naicha Frutilla',  'price' => 17.00],
            ['name' => 'Naicha Mango',     'price' => 17.00],
            ['name' => 'Naicha Maracuyá',  'price' => 17.00],
            ['name' => 'Naicha Matcha',    'price' => 20.00],
            ['name' => 'Taro Latte',       'price' => 22.00],
        ];

        foreach ($bebidas as $product) {
            Product::create([
                'branch_id' => $branch->id,
                'name'      => $product['name'],
                'price'     => $product['price'],
                'is_active' => true,
            ]);
        }

        // ── Toppings / Extras ─────────────────────────────────────
        $toppings = [
            ['name' => 'Tapioca Pearls',  'price' => 5.00],
            ['name' => 'Nata de Coco',    'price' => 5.00],
            ['name' => 'Pudding Jelly',   'price' => 5.00],
            ['name' => 'Oreo Crumbs',     'price' => 5.00],
            ['name' => 'Mango Boba',      'price' => 5.00],
            ['name' => 'Strawberry Boba', 'price' => 5.00],
            ['name' => 'Passion Boba',    'price' => 5.00],
            ['name' => 'Coffee Jelly',    'price' => 5.00],
        ];

        foreach ($toppings as $topping) {
            Product::create([
                'branch_id' => $branch->id,
                'name'      => $topping['name'],
                'price'     => $topping['price'],
                'is_active' => true,
            ]);
        }
    }
}