<?php

namespace App\Http\Controllers;

use App\Models\Votes;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VotesController extends Controller
{

    public function ListAllVotes(Request $request) {
        return Votes::all();
    }

    public function ListOwnedVotes(Request $request) {
        $id_user = $request ->post("id");
        return Votes::where('fk_id_user', $id_user)->get();;
    }
/*
    public function ListFollowedPosts(Request $request) {
        proximamente: se listaran todos los posts pertenecientes a usuarios seguidos por el user loggueado
    }

    public function ListDiscover(Request $request, $id_post) {
        proximamente: se listaran todos los posts pertenecientes a etiquetas de interes marcadas por el user loggueado
    }
*/

    public function CreateVote(Request $request) {
        $upvote = $request ->post("upvote");
        $downvote = $request ->post("downvote");
        if ($upvote == true) {
            return $this -> CreateUpvote($request);
        } else if ($downvote == true) {

        }
    }



    public function CreateUpvote(Request $request) {
        /*
            function obtenerId(Request $request) {
                $user = $request->user();
                $userId = $user->id;
            }
        */
            //obtenerId($request);
            
            
            //necesito $id_user y $id_post traidos (ambos vienen en el )
            $newVote = new Votes();
            $newVote -> fk_id_user = $request ->post("id_user");
            $newVote -> fk_id_post = $request ->post("id_post");
    //        $newVote -> fk_id_user = $request ->post("id_user");
    //        $newVote -> fk_id_post = $request ->post("id_post");
            $newVote -> upvote = true;
            $newVote -> downvote = false;

            $newVote -> save();
            return $newVote;
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

    public function Delete(Request $request, $id_post) {
        //falta conseguir el id del post
        $post = Post::findOrFail($id_post);
        $post -> delete();
        return [ "response" => "Object with ID $id_post deleted"];
    }





    /////////////////////////////////////////////////////
    /////////////////////////////////////////////////////
    /////////////////////////////////////////////////////










    public function EditVotesPost(Request $request, $id_user, $id_post){
        //$id_user = 3;
        //$id_post = 4;
        $user = Votes::where('fk_id_post', $id_post)
                     ->where('fk_id_user', $id_user)->get();
        
                   
        //$post = Votes::where('fk_id_post', $id_post)->get();


        //return $post;
        return $user;

        
    /*
        if ($user ) {

        }
    */

        
    /*
        $post = Post::findOrFail($id_post);
        $post -> votes = $request ->post("text"); 
        $post -> location = $request ->post("location");
        
        $post -> save();  
        return $post;
    */
    }






/*
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
*/

}
