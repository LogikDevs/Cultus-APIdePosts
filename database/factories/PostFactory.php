<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition()
    {
        return [
            'fk_id_user' => User::all()->random()->id,
            'text' => $this->faker->paragraph(),
            'location' => $this->faker->address(),
            'date' => $this->faker->dateTime()
        ];
    }
}
