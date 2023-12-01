<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {

        $pets = [
            [
                'name' => 'Pet1',
                'photoUrls' => json_encode([
                    ['https1'],
                    ['https2'],
                ]),
                'status' => 'available',
                'category_id' => 1
            ],
            [
                'name' => 'Pet2',
                'photoUrls' => json_encode([
                    ['https3'],
                    ['https4'],
                ]),
                'status' => 'available',
                'category_id' => 2
            ],

        ];

        DB::table('pets')->insert($pets);
    }
}
