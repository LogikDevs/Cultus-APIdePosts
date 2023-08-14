<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    public function run()
    {
        DB::table('post')->insert([
            'fk_id_user' => 1,
            'fk_id_event' => 1,
            'text' => 'HOLIWI',
            'latitud' => '2352626',
            'longitud' => '57568568',
            'date' => date('d-m-y H:i')
        ]);

        Post::factory(10)->create();
    }
}
