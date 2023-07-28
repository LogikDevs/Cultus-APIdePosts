<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Votes;
use Illuminate\Support\Facades\Auth;
use Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller 
{
    
/*
    public function RecieveUser (Request $request) {
        //
    }
*/

    public function ListAllPosts(Request $request) {
        return Post::all();
    }

    public function ListUserPosts(Request $request, $id_user) {
        return Post::where('fk_id_user', $id_user)->get();
    }

    public function ListOnePost(Request $request, $id_post) {
        return Post::where('id_post', $id_post)->first();
    }

/*
    public function ListFollowedPosts(Request $request) {
        se listaran todos los posts pertenecientes a usuarios seguidos por el user loggueado
    }

    public function ListDiscover(Request $request, $id_post) {
        se listaran todos los posts pertenecientes a etiquetas de interes marcadas por el user loggueado
    }
*/

    public function CreatePost(Request $request){
        $validation = [
            'text' => 'nullable | max:255',
            'location' => 'nullable | max:100',
            'fk_id_user' => 'required | exists:users,id',
        ];

        $request->validate($validation);
        return $this->savePost($request);
    }
            private function savePost(Request $request) {
                $newPost = new Post();
                $newPost -> text = $request->input('text');
                $newPost -> location = $request->input('location');
                $newPost -> fk_id_user = $request->input('id');
                $newPost -> date = date('d-m-y H:i');
                $newPost -> save();
            
                return $newPost;
            }

    public function Delete(Request $request, $id_post) {
        $post = Post::findOrFail($id_post);
        $post -> delete();
    }
}
