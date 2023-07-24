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

            $response = Http::get('http://localhost:8000/api/v1/interest');
            if ($response->successful()) {
                $interests = $response->json();
                $interest = collect($interests)->random();
                return [
                    'fk_id_label' => $interest['id_label'],
                    'fk_id_post' => Post::all()->random()->id_post
                ];
            } else {
                // Manejar el caso de error en la solicitud HTTP
                return [];
            }












        return [
            'fk_id_label' => interest::all()->random()->id_label,
            'fk_id_post' => Post::all()->random()->id_post
        ];
    }
}
