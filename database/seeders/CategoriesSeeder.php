<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {

        $categories = [
            ['name' => 'Category1'],
            ['name' => 'Category2'],
            ['name' => 'Category3'],
            ['name' => 'Category4'],
        ];

        DB::table('categories')->insert($categories);
    }
}
