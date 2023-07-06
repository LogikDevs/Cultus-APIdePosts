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

    public function ListOwnedVotes(Request $request, $id_user) {
        //me falta el id del usuario, no es el 
        //$id_user = 2;
        return Votes::where('fk_id_user', $id_user)->get();
    }






    public function CreateVote(Request $request) {
        //return Votes::all();
    }







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
