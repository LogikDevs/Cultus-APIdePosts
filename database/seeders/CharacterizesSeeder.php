<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Characterizes;

class CharacterizesSeeder extends Seeder
{
    public function run()
    {
        Characterizes::factory(10)->create();
    }
}
