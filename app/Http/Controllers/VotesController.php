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
        return Votes::where('fk_id_user', $id_user)->get();
    }

    public function ListPostVotes(Request $request, $id_post) {
        return Votes::where('fk_id_post', $id_post)->get();
    }

    public function CreateVote(Request $request) {
        $fk_id_user = $request->input('fk_id_user');
        $fk_id_post = $request->input('fk_id_post');

        $findVote = Votes::where('fk_id_user', $fk_id_user)
                             ->where('fk_id_post', $fk_id_post)
                             ->first();
    
        if ($findVote) {
            $findVote -> vote = $request -> input('vote');
            $findVote->save();
            //return $findVote;
        } else {
            $newVote = new Votes();
            $newVote -> fk_id_user = $fk_id_user;
            $newVote -> fk_id_post = $fk_id_post;
            $newVote -> vote = $request->input('vote');
            $newVote->save();
            // return $newVote;
        }
        
    /*
        $data = $request->all();
            $conjunto = [
                'fk_id_user' => $data['id_user'],
                'fk_id_post' => $data['id_post']
            ];
        $row = Votes::where($conjunto)->first();
    
        if ($row) {
            $row-> upvote = $data['upvote'];
            $row-> downvote = $data['downvote'];
            $row->save();
        } else {
            $newVote = new Votes();
            $newVote->fk_id_user = $datos['id_user'];
            $newVote->fk_id_user = $datos['id_post'];
            $newVote->upvote = $datos['upvote'];
            $newVote->downvote = $datos['downvote'];

            $newVote->save();
        }
    */
    }

    public function Delete(Request $request, $id_vote) {
        $vote = Votes::findOrFail($id_vote);
        $vote -> delete();
        return [ "response" => "Object with ID $id_vote deleted"];
    }
}