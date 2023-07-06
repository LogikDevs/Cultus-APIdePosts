<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;





class PostController extends Controller {
    
    
    
    public function RecieveUser (Request $request) {
        //return Post::all();
    }




    public function ListAllPosts(Request $request) {
        return Post::all();
    }

    public function ListOwnedPosts(Request $request) {
        //me falta el id del usuario, no es el 
        //$id_user = 2;
        $id_user = $request ->post("id");
        return Post::where('fk_id_user', $id_user)->get();;
    }

    public function Delete(Request $request, $id_post) {
        //tengo que conseguir el id del post
        $post = Post::findOrFail($id_post);
        $post -> delete();
        return [ "response" => "Object with ID $id_post deleted"];
    }



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

/*
    public function ListFollowedPosts(Request $request) {
        proximamente: se listaran todos los posts pertenecientes a usuarios seguidos por el user loggueado
    }

    public function ListDiscover(Request $request, $id_post) {
        proximamente: se listaran todos los posts pertenecientes a etiquetas de interes marcadas por el user loggueado
    }
*/





/*
//NO SE SI VA PORQUE UN POST NO SE DEBERIA PODER MODIFICAR
    public function Edit(user $request, $id_post){
        $post = Post::findOrFail($id_post);
        $post -> text = $request ->post("text"); 
        $post -> location = $request ->post("location");
        
        $post -> save();  
        return $post;
    }
*/

}
