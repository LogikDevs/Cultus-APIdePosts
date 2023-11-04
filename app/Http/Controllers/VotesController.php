<?php

namespace App\Http\Controllers;

use App\Models\Votes;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class VotesController extends Controller
{
    public function GetUserLogged(Request $request) {
        return $request->input('user');
    }

    public function ListAllVotes(Request $request) {
        return Votes::all();
    }

    public function ListOwnedVotes(Request $request) {
        $user = $this->GetUserLogged($request);
        return Votes::where('fk_id_user', $user['id'])->get();
    }

    public function ListPostVotes(Request $request, $id_post) {
        return Votes::where('fk_id_post', $id_post)->get();
    }

    public function ValidateVote(Request $request) {
        $validator = $this->ValidateData($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        return $this->CreateVote($request);
    }

    public function ValidateData(Request $request) {
        return Validator::make($request->all(), [
            'fk_id_post'=>'required | exists:post,id_post',
            'vote'=>'required | boolean'
        ]);
    }

    public function CreateVote(Request $request) {
        $user = $this->GetUserLogged($request);
        $post = Post::find($request['fk_id_post']);

        return $this->UpdateCreateVote($post, $user['id'], $request['vote']);
        $this->UpdateVoteCount($post);
    }

    private function UpdateCreateVote(Post $post, $id_user, $vote) {
        $existingVote = $post->votes()
            ->where('fk_id_user', $id_user)
            //->withTrashed()
            ->first();
            
        if ($existingVote) {
            return $this->RestoreVote($existingVote, $post, $id_user, $vote);
        }

        $post->votes()->create([
            'vote' => $vote,
            'fk_id_user' => $id_user,
        ]);
        return "El voto no existia y se acaba de crear";
    }

    public function RestoreVote($existingVote, $post, $id_user, $vote) {
        //$existingVote->restore();
        echo "El voto ya existe y fue restaurado";
        if ($existingVote -> vote != $vote) {
            $existingVote -> vote = $vote;
            $existingVote -> save();
            return "El voto es diferente al que ya existia y se modifico";
        } else if ($existingVote -> vote == $vote){
            $existingVote->delete();
            return "El voto es igual al anterior y se elimino";
        }
    }

/*
    private function up(Post $post, $id_user, $vote) {
        if ($existingVote->vote != $vote) {
            $existingVote->vote = $vote;
            $existingVote->save();
        } else {
            $existingVote->delete();
        }
    }
*/

    private function UpdateVoteCount(Post $post) {
        $votesCount = $post->votes()->where('vote', true)->count() - $post->votes()->where('vote', false)->count();
        $post->votes = $votesCount;
        $post->save();
    }
}