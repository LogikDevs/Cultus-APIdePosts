<?php

namespace Tests\Feature;

//use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    //use RefreshDatabase;
/*
    private $BearerToken;
    
    public function setUp() :void{
        parent::setUp();

        $this->userName = getenv("USERNAME");
        $this->userPassword = getenv("USERPASSWORD");
        $this->clientId = getenv("CLIENTID");
        $this->clientSecret = getenv("CLIENTSECRET");

        $tokenHeader = [ "Content-Type" => "application/json"];
        $Bearer = Http::withHeaders($tokenHeader)->post(getenv("API_AUTH_URL") . "/oauth/token",
        [
            'username' => $this->userName,
            'password' => $this->userPassword,
            "grant_type" => "password",
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ])->json();

        $this->BearerToken = $Bearer['access_token'];
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->BearerToken]);
    }
*/

    public function test_ListPosts(){
        // Autenticar y obtener un token válido
        $response = $this->post(config('API_AUTH_URL') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => 101,
            'client_secret' => 'IXxEgA3rSj8JlxOsmFeMr9dklbxK7jlVuv3Xcujc',
            'username' => 'usuario@email.com',
            'password' => '12345678',
        ]);

        $token = $response->json('access_token');

        // Realizar una solicitud a una ruta protegida con el token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/v1/posts');

        $response->assertStatus(200);

/*
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->api_token,
        ])->get('/api/v1/posts');

        //$response = $this->get('/api/v1/posts');
        $response -> assertStatus(200);
*/
    }



/*
    public function test_ListUserPostGoodRequest() {
        $response = $this->get('/api/post/listUser/4');
        
        //dd($response->json());
        //$response -> assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                "id_post",
                "fk_id_user",
                "text",
                "latitud",
                "longitud",
                "date",
                "votes",
                "comments",
                "created_at",
                "updated_at",
                "deleted_at"
            ]
        ]);
        $response -> assertJsonFragment([
            "id_post"=> 9,
            "fk_id_user"=> 4,
            "deleted_at"=> null
        ]);
    }


    public function test_ListUserPostOneThatDoesntExist(){
        $response = $this->get('/api/post/listUser/10000');
        $response -> assertStatus(404);
    }
*/
}
