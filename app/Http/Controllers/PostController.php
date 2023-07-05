<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;





class PostController extends Controller
{







    public function ListAllPosts(Request $request) {
        return Post::all();
    }

    public function ListOwnedPosts(Request $request, $id_post) {
        //me falta el id del usuario
        return Post::findOrFail($id_post);
    }


/*
    public function ListFollowed(Request $request) {
        //followed preciso el tokennnnnnn
        return Post::findOrFail($id_post);
    }

    public function ListDiscover(Request $request, $id_post) {
        // preciso el tokennnnnnn
        return Post::findOrFail($id_post);
    }
*/


    public function PostCreate(Request $request){
        $validation = self::CreatePostValidation($request);

        if ($validation->fails())
        return $validation->errors();

        return $this -> CreatePost($request);
    }


public function CreatePostValidation(Request $request){
    $validation = Validator::make($request->all(),[
        'text' => 'nullable | alpha:ascii | max:255',
        'location' => 'nullable | alpha:ascii',



        'age' => 'required | integer',
        'gender' => 'nullable | alpha',
        'email' => 'email | required | unique:users',
        'password' =>'required | min:8 | confirmed',
        'profile_pic' => 'nullable',
        'description' => 'nullable | max:255',
        'homeland' => ' nullable | integer | exists:country,id_country',
        'residence' => 'nullable | integer | exists:country,id_country'
    ]);
    return $validation;    
}

    public function CreatePost(Request $request) {
        $nuevoPost = new Post();
        $nuevoPost -> text = $request ->post("text");
        $nuevoPost -> location = $request ->post("location");
        //$nuevoPost -> fk_id_user = $request ->post("fk_id_user");         como hago? con el token?
        //votos se deja vacio, porque apenas se crea el post no tiene votos de ningun tipo 
        //date tambien esta vacio porque se guarda el datetime (curdate), pero se puede con laravel?
        $nuevoPost -> date = Carbon::now()->toTimeString();

        $nuevoPost -> save();
        return $nuevoPost;
    }




    public function Delete(Request $request, $id_post) {
        $post = Post::findOrFail($id_post);
        $post -> delete();
        return [ "response" => "Object with ID $id_post deleted"];
    }



//NO SE VA PORQUE UN POST NO SE DEBERIA PODER MODIFICAR
    public function Edit(user $request, $id_post){
        $post = Post::findOrFail($id_post);
        $post -> text = $request ->post("text"); 
        $post -> location = $request ->post("location");
        
        $post -> save();  
        return $post;
    }
}
