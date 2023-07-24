<?php

namespace Database\Factories;

use App\Models\MultimediaPost;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class MultimediaPostFactory extends Factory
{
    public function definition()
    {
        return [
            'fk_id_post' => Post::all()->random()->id_post,
            'multimediaLink' => $this->faker->url('http')
        ];
    }
}
