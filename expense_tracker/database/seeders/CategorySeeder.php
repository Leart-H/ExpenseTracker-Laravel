<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Ushqim', 'Transport', 'Argëtim', 'Qira', 'Tjera'];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
