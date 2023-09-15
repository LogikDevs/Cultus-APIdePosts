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
    public function GetUserId(Request $request) {
        $tokenHeader = [ "Authorization" => $request -> header("Authorization")];
        $response = Http::withHeaders($tokenHeader)->get(getenv("API_AUTH_URL") . "/api/v1/validate");
        return $response['id'];
    }

    public function ListAllVotes(Request $request) {
        return Votes::all();
    }

    public function ListOwnedVotes(Request $request) {
        $id_user = $this->GetUserId($request);
        return Votes::where('fk_id_user', $id_user)->get();
    }

    public function ListPostVotes(Request $request, $id_post) {
        return Votes::where('fk_id_post', $id_post)->get();
    }

    public function ValidateVote(Request $request) {
        $validator = Validator::make($request->all(), [
            'fk_id_post'=>'required | exists:post,id_post',
            'vote'=>'required | boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        return $this->CreateVote($request);
    }

    public function CreateVote(Request $request) {
        $id_user = $this->GetUserId($request);
        $id_post = $request->input('fk_id_post');
        $vote = $request->input('vote');

        $post = Post::find($id_post);

        $this->UpdateCreateVote($post, $id_user, $vote);
        $this->UpdateVoteCount($post);
    }

    private function UpdateCreateVote(Post $post, $id_user, $vote) {
        $existingVote = $post->votes()
            ->where('fk_id_user', $id_user)
            ->withTrashed()
            ->first();

        if ($existingVote) {
            if ($existingVote->trashed()) {
                $existingVote->restore();
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
        $post = $vote->post;

        $vote->delete();
        $this->UpdateVoteCount($post);
    }

    private function UpdateVoteCount(Post $post) {
        $votesCount = $post->votes()->where('vote', true)->count() - $post->votes()->where('vote', false)->count();
        $post->votes = $votesCount;
        $post->save();
    }
}