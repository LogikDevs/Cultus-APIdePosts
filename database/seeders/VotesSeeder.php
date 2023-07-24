<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Votes;

class VotesSeeder extends Seeder
{
    public function run()
    {
        Votes::factory(10)->create();
    }
}
