<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdutosTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('produtos')->truncate();

        $produtos = [
            [
                'nome' => 'Notebook Lenovo IdeaPad 3',
                'descricao' => 'Notebook 15.6" Ryzen 5, 8GB RAM, 256GB SSD',
                'preco' => 2999.90,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Smartphone Samsung Galaxy S23',
                'descricao' => 'Tela 6.1", 128GB, 5G, 8GB RAM',
                'preco' => 4299.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Impressora HP DeskJet Ink Advantage',
                'descricao' => 'Impressora multifuncional Wi-Fi',
                'preco' => 399.99,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Cadeira Gamer ThunderX3',
                'descricao' => 'Cadeira ergonômica, couro sintético',
                'preco' => 1199.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Monitor LG 24"',
                'descricao' => 'Monitor LED Full HD, HDMI',
                'preco' => 799.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('produtos')->insert($produtos);
    }
}
