<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Fiction','Science','Technology','History','Philosophy','Business',
        ];

        foreach ($names as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}

