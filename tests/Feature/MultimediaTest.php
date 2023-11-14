<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MultimediaPost extends TestCase
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

    public function test_ListMultimedia(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->get('/api/v1/multimedia');

        $response->assertStatus(200);
    }

    public function test_CreateMultimediaBadRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->post('/api/v1/multimedia/create', [
            "fk_id_post" => "",
            "multimediaLink" => ""
        ]);
        $response -> assertStatus(400);
        $response -> assertJsonFragment([
            "fk_id_post"=> ["The fk id post field is required."],
            "multimedia_file"=> ["The multimedia file field is required."]
        ]);
    }
}
