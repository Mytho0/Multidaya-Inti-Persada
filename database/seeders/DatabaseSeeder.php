<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan seeder harus memperhatikan foreign key
        $this->call([
            PelangganSeeder::class,      
            BarangSeeder::class,         
            PeminjamanSeeder::class,     
            BiayaOperasionalSeeder::class,
            NotificationSeeder::class,    
            RecommendationSeeder::class,  
        ]);
    }
}