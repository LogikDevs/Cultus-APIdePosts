<?php

namespace Tests\Feature;

//use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostTest extends TestCase
{
    use DatabaseTransactions;
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
        //
        //dd(getenv("NAME"));
        //dd($Bearer);
        $this->BearerToken = $Bearer['access_token'];
    }

    public function test_ListPosts(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->get('/api/v1/posts');

        $response->assertStatus(200);
    }

    public function test_ListPostsFromEvents(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->get('/api/v1/posts/event/1');
        $response -> assertStatus(200);
    }

    public function test_ListOnePostThatExist(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->get('/api/v1/posts/list/1');
        $response -> assertStatus(200);

        $response->assertJsonStructure([
            'user'  => [
                "id", "name", "surname", "profile_pic",
            ],
            'post' => [
                "id_post", "fk_id_user", "fk_id_event", "text", 
                "latitud", "longitud", "date", "votes", "comments", 
                "created_at", "updated_at", "deleted_at"
            ],
            'multimedia' => [ [
                "id_multimediaPost", "fk_id_post", "multimediaLink", 
                "created_at", "updated_at", "deleted_at"
            ] ],
            'interests' => [],
            'comments' => [ [
                "id_comment", "text",
                "user" => [
                    "id", "name", "surname"
                ]
            ] ]
        ]);
    }

    public function test_ListOnePostThatDoesntExist(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->get('/api/v1/posts/list/100000');
        $response -> assertStatus(404);
    }

    public function test_ListUserPostsGoodRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->get('/api/v1/posts/user/1');
        $response -> assertStatus(200);
    }

    public function test_ListUserPostsBadRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->get('/api/v1/posts/user/100000');
        $response -> assertStatus(404);
    }

    public function test_ListFollowed(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->get('/api/v1/posts/followed');
        $response -> assertStatus(200);
    }

    public function test_ListInterested(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->get('/api/v1/posts/interested');
        $response -> assertStatus(200);
    }

    public function test_CreatePostGoodRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->post('/api/v1/posts/create', [
            "text" => "texto"
        ]);
        $response -> assertStatus(201);
        $response -> assertJsonStructure([
                "text",
                "fk_id_user"
        ]);
        $this->assertDatabaseHas('post', [
            "text" => "texto"
        ]);
    }

    public function test_CreatePostBadRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->post('/api/v1/posts/create', [
            "text" => ""
        ]);
        $response -> assertStatus(400);
        $response -> assertJsonFragment([
            "text"=> ["The text field is required."]
        ]);
    }

/*
    public function test_DeletePostGoodRequest(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->delete('/api/v1/posts/delete/1');
        $response -> assertStatus(400);
        $response -> assertJsonFragment([
                "text"=> ["The text field is required."]
        ]);
    }
*/
    public function test_DeletePostBadRequestNotFound(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->delete('/api/v1/posts/delete/100000');
        $response -> assertStatus(404);
    }
}
