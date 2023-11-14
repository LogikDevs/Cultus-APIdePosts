<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class VotesTest extends TestCase
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

    public function test_ListVotes(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->get('/api/v1/votes');

        $response->assertStatus(200);
    }

    public function test_CreateVotesGoodRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->post('/api/v1/votes/create', [
            "vote" => 1,
            "fk_id_post" => 2
        ]);
        $response -> assertStatus(201);
        $response -> assertJsonStructure([
                "vote",
                "fk_id_post"
        ]);
        $this->assertDatabaseHas('votes', [
            "vote" => 1,
            "fk_id_post" => 2
        ]);
    }

    public function test_DeleteVotesGoodRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->post('/api/v1/votes/create', [
            "vote" => 1,
            "fk_id_post" => 2
        ]);
        $response -> assertStatus(200);
        $response->assertSee('2');
    }

    public function test_CreateVotesBadRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->post('/api/v1/votes/create', [
            "vote" => "",
            "fk_id_post" => ""
        ]);
        $response -> assertStatus(400);
        $response -> assertJsonFragment([
            "vote"=> ["The vote field is required."],
            "fk_id_post"=> ["The fk id post field is required."]
        ]);
    }
}
