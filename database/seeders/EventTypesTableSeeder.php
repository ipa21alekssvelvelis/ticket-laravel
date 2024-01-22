<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('event_types')->insert([
            'type' => 'Cars and Racing',
        ]);
        DB::table('event_types')->insert([
            'type' => 'Health and Medicine',
        ]);
        DB::table('event_types')->insert([
            'type' => 'Sports and Competition',
        ]);
        DB::table('event_types')->insert([
            'type' => 'Technology and Engineering',
        ]);
        DB::table('event_types')->insert([
            'type' => 'Food and Drink',
        ]);
        DB::table('event_types')->insert([
            'type' => 'Music and Dance',
        ]);
    }
}
