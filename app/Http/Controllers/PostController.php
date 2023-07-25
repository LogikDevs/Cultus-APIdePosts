<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller {

    public function RecieveUser (Request $request) {
        //
    }

    public function ListAllPosts(Request $request) {
        return Post::all();
    }

    public function ListUserPosts(Request $request, $id_user) {
        $postsUser = Post::where('fk_id_user', $id_user)->get();
        return $postsUser;
    /*
        $postList = [];
        foreach ($postsUser as $post) {
            $postList[] = [
                'text' => $post->text,
                'location' => $post->location,
            ];
        }

        return $postList;
    */
    }

/*
    public function ListFollowedPosts(Request $request) {
        se listaran todos los posts pertenecientes a usuarios seguidos por el user loggueado
    }

    public function ListDiscover(Request $request, $id_post) {
        se listaran todos los posts pertenecientes a etiquetas de interes marcadas por el user loggueado
    }
*/


    public function PostCreate(Request $request){
        $reglasValidacion = [
            'text' => 'nullable | max:255',
            'location' => 'nullable | max:255',
        ];

        $request->validate($reglasValidacion);

        $nuevoPost = new Post();
        $nuevoPost->text = $request->input('text');
        $nuevoPost->location = $request->input('location');
        $nuevoPost->fk_id_user = $request->input('fk_id_user');
        $nuevoPost -> date = date('d-m-y H:i');
        $nuevoPost->save();

        return $nuevoPost;
    }

/*
    public function PostCreate(Request $request){
        $validation = self::CreatePostValidation($request);
        if ($validation->fails())
        return $validation->errors();
        return $this -> CreatePost($request);
    }

                public function CreatePostValidation(Request $request){
                    $validation = Validator::make($request->all(),[
                        'text' => 'nullable | max:255',
                        'location' => 'nullable | max:255',
                    ]);
                    return $validation;    
                }

                public function CreatePost(Request $request) {
                    $nuevoPost = new Post();
                    $now = date('d-m-y H:i');
                    $nuevoPost -> text = $request ->post("text");
                    $nuevoPost -> location = $request ->post("location");
                    $nuevoPost -> fk_id_user = $request ->post("id");
                    $nuevoPost -> date = $now;

                    $nuevoPost -> save();
                    return $nuevoPost;
                }
*/

    public function Delete(Request $request, $id_post) {
        $post = Post::findOrFail($id_post);
        $post -> delete();
        return [ "response" => "Object with ID $id_post deleted"];
    }

}
