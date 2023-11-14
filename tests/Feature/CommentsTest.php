<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CommentsTest extends TestCase
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

    public function test_ListComments(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->get('/api/v1/comments');

        $response->assertStatus(200);
    }

    public function test_CreateCommentsGoodRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->post('/api/v1/comments/create', [
            "fk_id_post" => 1,
            "text" => "COMENTARIO A PROBAR",
        ]);
        $response -> assertStatus(201);
        $response->assertJsonStructure([
            'comment'  => [
                "fk_id_user", "fk_id_post", "text", 
                "created_at", "updated_at",
                "id_comment"
            ],
            'user' => [
                "id", "name", "surname", "profile_pic"
            ],
        ]);
        $this->assertDatabaseHas('comments', [
            "fk_id_post" => 1,
            "text" => "COMENTARIO A PROBAR"
        ]);
    }

    public function test_CreateCommentsBadRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->post('/api/v1/comments/create', [
            "fk_id_post" => "",
            "text" => ""
        ]);
        $response -> assertStatus(400);
        $response -> assertJsonFragment([
            "fk_id_post"=> ["The fk id post field is required."],
            "text"=> ["The text field is required."]
        ]);
    }

    public function test_DeleteCommentsBadRequestNotFound(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->delete('/api/v1/comments/delete/100000');
        $response -> assertStatus(404);
    }

    public function test_DeleteCommentsGoodRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->delete('/api/v1/comments/delete/1');
        $response -> assertStatus(200);
    }
}
