<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use App\Models\Votes;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class VotesFactory extends Factory
{
    public function definition()
    {
        return [
            'fk_id_user' => User::all()->random()->id,
            'fk_id_post' => Post::all()->random()->id_post,
            'vote' => $this->faker->boolean
        ];
    }
}
