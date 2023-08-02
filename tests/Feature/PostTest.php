<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    public function test_ListUserPostGoodRequest() {
        $response = $this->get('/api/post/listUser/1');
        
        $response -> assertStatus(200);
        $response->assertJsonStructure([
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
        ]);
        $response -> assertJsonFragment([
            "id_post"=> 1,
            "fk_id_user"=> 1,
            "deleted_at"=> null
        ]);
    }


    public function test_ListUserPostOneThatDoesntExist(){
        $response = $this->get('/api/post/listUser/10000');
        $response -> assertStatus(404);
    }
}
