<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = ['crypto', 'hot', 'scam', 'etf'];

        foreach ($tags as $name) {
            DB::table('tags')->updateOrInsert(['name' => $name]);
        }
    }
}
