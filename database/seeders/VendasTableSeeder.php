<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VendasTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('vendas')->truncate();

        DB::table('vendas')->insert([
            [
                'cliente_id' => 1,
                'user_id' => 1,
                'total' => 100.00,
                'forma_pagamento' => 'Dinheiro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'cliente_id' => 2,
                'user_id' => 1,
                'total' => 199.99,
                'forma_pagamento' => 'Cartão',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
