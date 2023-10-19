<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\MultimediaPost;

class MultimediaPostSeeder extends Seeder
{
    public function run()
    {
        DB::table('multimedia_post')->insert([
            'multimediaLink' => '/public/image',
            'fk_id_post' => 1
        ]);

        MultimediaPost::factory(10)->create();
    }
}
