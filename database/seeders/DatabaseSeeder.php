<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(PostSeeder::class);
        $this->call(MultimediaPostSeeder::class);
        $this->call(CharacterizesSeeder::class);
        $this->call(VotesSeeder::class);
        $this->call(CommentsSeeder::class);
    }
}
