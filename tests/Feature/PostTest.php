<?php

namespace Tests\Feature;

//use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    //use RefreshDatabase;
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
}
