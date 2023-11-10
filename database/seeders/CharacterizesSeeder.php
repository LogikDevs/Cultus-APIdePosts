<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Characterizes;

class CharacterizesSeeder extends Seeder
{

    private function CreatePosts() {
        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            DB::table('post')->insert([
                'fk_id_user'=> random_int(1, 10),
                'text' => $faker->sentence,
                'date' => $faker->dateTime(),
                'votes' => $faker->numberBetween(0, 500),
                'comments' => $faker->numberBetween(0, 500)
            ]);
        }
    }

    private function CreateInterests() {
        DB::table('interest_label')->insert([
            ['interest' => 'arte'],
            ['interest' => 'gastronomia'],
            ['interest' => 'deportes'],
            ['interest' => 'historia'],
            ['interest' => 'musica'],
            ['interest' => 'jardineria'],
            ['interest' => 'tecnologia'],
            ['interest' => 'astronomia'],
            ['interest' => 'lectura'],
            ['interest' => 'cine'],
        ]);
    }

    public function run()
    {
        $this -> CreatePosts();
        $this -> CreateInterests();

        DB::table('characterizes')->insert([
            'fk_id_label' => 1,
            'fk_id_post' => 1
        ]);

        DB::table('likes')->insert([
            'id_user' => 11,
            'id_interest' => 1
        ]);

        Characterizes::factory(10)->create();
    }
}
