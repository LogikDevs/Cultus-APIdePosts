<?php

namespace Database\Factories;

//use App\Models\interest;
use App\Models\Post;
use App\Models\Characterizes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

class CharacterizesFactory extends Factory
{
    public function definition()
    {
        return [
            'fk_id_post' => Post::all()->random()->id_post,
            'fk_id_label' => random_int(1, 10)
        ];
    }
}
