<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TagsSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(PetsSeeder::class);
        $this->call(PetHasTagSeeder::class);
    }
}
