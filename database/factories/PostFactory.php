<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

class PostFactory extends Factory
{
    public function definition()
    {
        $text = $this->faker->paragraph();
        $text = Str::limit($text, 500);

        return [
            'fk_id_user' => random_int(1, 10),
            'fk_id_event' => random_int(1, 10),
            'text' => $text,
            'latitud' => $this->faker->latitude(),
            'longitud' => $this->faker->longitude(),
            'date' => $this->faker->dateTime()
        ];
    }
}
