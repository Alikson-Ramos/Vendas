<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            ClientesTableSeeder::class, // php artisan db:seed --class=ClientesTableSeeder
            ProdutosTableSeeder::class, // php artisan db:seed --class=ProdutosTableSeeder

        ]);
    }
}
