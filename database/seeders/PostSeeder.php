<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\Post;

class PostSeeder extends Seeder
{
    private function CreateUsers() {
        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            DB::table('users')->insert([
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'age' => $faker->numberBetween(0, 90),
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password')
            ]);
        }
    }

    private function CreateEvents() {
        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            DB::table('events')->insert([
                'name' => $faker->word,
                'text' => $faker->sentence,
                'start_date' => $faker->dateTime(),
                'end_date' => $faker->dateTime(),
                'private' => $faker->boolean
            ]);
        }
    }



    public function run()
    {      
        DB::table('users')->insert([
            'name' => 'logik',
            'surname' => 'devs',
            'age' => 20,
            'email' => 'logikdevs@gmail.com',
            'password' => '12345678'
        ]);

        $this -> CreateUsers();
        $this -> CreateEvents();
        
        DB::table('post')->insert([
            'fk_id_user' => 1,
            'fk_id_event' => 1,
            'text' => 'TEXTO PARA TESTING',
            'latitud' => 1,
            'date' => date('01-12-23 00:00:00')
        ]);

        Post::factory(10)->create();
    }
}
