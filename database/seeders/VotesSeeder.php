<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Votes;

class VotesSeeder extends Seeder
{
    public function run()
    {
        DB::table('votes')->insert([
            'fk_id_user' => 2,
            'fk_id_post' => 1,
            'vote' => 1
        ]);

        Votes::factory(10)->create();
    }
}
