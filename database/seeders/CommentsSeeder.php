<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Comments;

class CommentsSeeder extends Seeder
{
    public function run()
    {
        DB::table('comments')->insert([
            'fk_id_user' => 11,
            'fk_id_post' => 1,
            'text' => 'COMENTARIO PARA TESTING'
        ]);

        Comments::factory(10)->create();
    }
}
