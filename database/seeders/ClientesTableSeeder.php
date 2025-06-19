<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clientes')->truncate();

        $clientes = [
            [
                'nome'       => 'João Silva',
                'email'      => 'joao.silva@email.com',
                'telefone'   => '11998761234',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome'       => 'Maria Oliveira',
                'email'      => 'maria.oliveira@email.com',
                'telefone'   => '21999874563',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome'       => 'Carlos Pereira',
                'email'      => 'carlos.pereira@email.com',
                'telefone'   => '31996451278',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome'       => 'Ana Souza',
                'email'      => 'ana.souza@email.com',
                'telefone'   => '41999988877',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome'       => 'Felipe Martins',
                'email'      => 'felipe.martins@email.com',
                'telefone'   => '11996665432',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('clientes')->insert($clientes);
    }
}
