<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->get();
        DB::table('users')->insert($users->toArray());

        $events = DB::table('events')->get();
        DB::table('events')->insert($events->toArray());

        $event_types = DB::table('event_types')->get();
        DB::table('event_types')->insert($event_types->toArray());

        $personal_access_tokens = DB::table('personal_access_tokens')->get();
        DB::table('personal_access_tokens')->insert($personal_access_tokens->toArray());
    }
}
