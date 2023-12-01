<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {

        $tags = [
            ['name' => 'Tag1'],
            ['name' => 'Tag2'],
            ['name' => 'Tag3'],
        ];

        DB::table('tags')->insert($tags);
    }
}
