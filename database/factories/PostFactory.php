<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition()
    {
        /*
            return [
                'fk_id_user' => User::all()->random()->id,
                'text' => $this->faker->paragraph(),
                'latitud' => $this->faker->latitude(),
                'longitud' => $this->faker->longitude(),
                'date' => $this->faker->dateTime()
            ];
        */

        $response = Http::get('http://localhost:8003/api/events');
        if ($response->successful()) {
            $events = $response->json();
            $event = collect($events)->random();
            return [
                'fk_id_user' => User::all()->random()->id,
                'fk_id_event' => $event['id_event'],            'fk_id_user' => User::all()->random()->id,
                'text' => $this->faker->paragraph(),
                'latitud' => $this->faker->latitude(),
                'longitud' => $this->faker->longitude(),
                'date' => $this->faker->dateTime()
            ];
        } else {
            return [];
        }
    }
}
