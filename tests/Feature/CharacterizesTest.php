<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CharacterizesTest extends TestCase
{
    private $BearerToken;
        
    public function setUp() :void{
        parent::setUp();

        $tokenHeader = [ "Content-Type" => "application/json"];
        $Bearer = Http::withHeaders($tokenHeader)->post(getenv("API_AUTH_URL") . "/oauth/token", [
            'username' => getenv("NAME"),
            'password' => getenv("USERPASSWORD"),
            "grant_type" => "password",
            'client_id' => getenv("CLIENTID"),
            'client_secret' => getenv("CLIENTSECRET"),
        ])->json();
        $this->BearerToken = $Bearer['access_token'];
    }

    public function test_CreateCharacterizesGoodRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->post('/api/v1/characterizes/create', [
            "fk_id_label" => 5,
            "fk_id_post" => 6
        ]);
        $response -> assertStatus(201);
        $response -> assertJsonStructure([
                "fk_id_label",
                "fk_id_post"
        ]);
        $this->assertDatabaseHas('characterizes', [
            "fk_id_label" => 5,
            "fk_id_post" => 6
        ]);
    }

    public function test_CreateCharacterizesBadRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->post('/api/v1/characterizes/create', [
            "fk_id_label" => "",
            "fk_id_post" => ""
        ]);
        $response -> assertStatus(400);
        $response -> assertJsonFragment([
            "fk_id_label"=> ["The fk id label field is required."],
            "fk_id_post"=> ["The fk id post field is required."]
        ]);
    }
}
