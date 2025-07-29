<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeedKeyword;

class FeedKeywordSeeder extends Seeder
{

    /**
     * Seed the feed_keywords table.
     *
     * @return void
     */
    public function run(): void
    {
        $keywords = [
            ['keyword' => 'bitcoin', 'type' => 'coin'],
            ['keyword' => 'btc', 'type' => 'coin'],
            ['keyword' => 'etf', 'type' => 'etf'],
            ['keyword' => 'ransomware', 'type' => 'hack'],
            ['keyword' => 'scam', 'type' => 'scam'],
        ];

        foreach ($keywords as $data) {
            FeedKeyword::firstOrCreate(['keyword' => $data['keyword']], $data);
        }
    }
}
