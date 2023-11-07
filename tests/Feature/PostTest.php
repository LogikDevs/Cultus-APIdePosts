<?php

namespace Tests\Feature;

//use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PostTest extends TestCase
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
    }

/*
    public function test_ListOnePostThatExist(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->BearerToken,
        ])->get('/api/v1/posts/list/1');
        $response -> assertStatus(200);

        $response->assertJsonStructure([
            //'*' => [
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
            //]
        ]);

        $response -> assertJsonFragment([
            'user'  => [
                "id", "name", "surname", "profile_pic",
            ],
            'post' => [
                "id_post" => 1,
                "fk_id_user", "fk_id_event", "text", 
                "latitud", "longitud", "date", "votes", "comments",
                "created_at", "updated_at", 
                "deleted_at" => null
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



    /*
            'post' => [
                "id_post"=> 1,
                "deleted_at"=> null
            ]
*/



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
}
