<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Country::create([
                'id' => $country['cca2'], // Code ISO alpha-2 comme ID
                'name' => $country['name']['common'],
                'flag' => $country['flags']['png'] ?? null,
            ]);
    }
}
