<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentsFactory extends Factory
{
    public function definition()
    {
        return [
            'fk_id_user' => User::all()->random()->id,
            'fk_id_post' => Post::all()->random()->id_post,
            'text' => $this->faker->text()
        ];
    }
}
