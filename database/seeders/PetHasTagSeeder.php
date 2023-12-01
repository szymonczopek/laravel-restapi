<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetHasTagSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {

        $pet_has_tag = [
            [
               'pet_id'=>1,
               'tag_id'=>1,
            ],
            [
                'pet_id'=>1,
                'tag_id'=>2,
            ],
            [
                'pet_id'=>1,
                'tag_id'=>3,
            ],
            [
                'pet_id'=>2,
                'tag_id'=>1,
            ],
            [
                'pet_id'=>2,
                'tag_id'=>2,
            ],

        ];

        DB::table('pet_has_tag')->insert($pet_has_tag);
    }
}
