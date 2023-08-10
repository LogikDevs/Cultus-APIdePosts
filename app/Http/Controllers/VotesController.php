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

/*
    public function CreateVote(Request $request) {
        $fk_id_user = $request->input('fk_id_user');
        $fk_id_post = $request->input('fk_id_post');
        $vote = $request->input('vote');

        $post = Post::find($fk_id_post);


        $existingVote = $post->votes()->where('fk_id_user', $fk_id_user)->first();
        if ($existingVote) {
            $existingVote->vote = $request->input('vote');
            $existingVote->save();
        } else {
            $post->votes()->create([
                'vote' => $request->input('vote'),
                'fk_id_user' => $fk_id_user,
            ]);
        }

        $votesCount = $post->votes()->where('vote', true)->count() - $post->votes()->where('vote', false)->count();
        $post->votes = $votesCount;
        $post->save();

        return response()->json(['message' => 'Voto registrado exitosamente']);
    }
*/

    public function CreateVote(Request $request) {
        $id_user = $request->input('fk_id_user');
        $id_post = $request->input('fk_id_post');
        $vote = $request->input('vote');

        $post = Post::find($id_post);

        $this->UpdateCreateVote($post, $id_user, $vote);
        $this->UpdateVoteCount($post);
    }

    /*
    private function UpdateCreateVote(Post $post, $id_user, $vote) {
        $existingVote = $post->votes()->where('fk_id_user', $id_user)->first();

        if ($existingVote) {
            $existingVote->vote = $vote;
            $existingVote->save();
        } else {
            $post->votes()->create([
                'vote' => $vote,
                'fk_id_user' => $id_user,
            ]);
        }
    }
*/

private function UpdateCreateVote(Post $post, $id_user, $vote) {
    $existingVote = $post->votes()
        ->where('fk_id_user', $id_user)
        ->withTrashed()//filas con deleted_at no nulo
        ->first();

    if ($existingVote) {
        if ($existingVote->trashed()) {
            $existingVote->restore(); //restauro el vote q elimine
        }
        $existingVote->vote = $vote;
        $existingVote->save();
    } else {
        $post->votes()->create([
            'vote' => $vote,
            'fk_id_user' => $id_user,
        ]);
    }
}

    public function Delete(Request $request, $id_vote) {
        $vote = Votes::findOrFail($id_vote);
        $vote -> delete();
    }

    private function UpdateVoteCount(Post $post) {
        $votesCount = $post->votes()->where('vote', true)->count() - $post->votes()->where('vote', false)->count();
        $post->votes = $votesCount;
        $post->save();
    }
}