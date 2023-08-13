<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MultimediaPost;

class MultimediaPostSeeder extends Seeder
{
    public function run()
    {
        MultimediaPost::factory(10)->create();
    }
}
