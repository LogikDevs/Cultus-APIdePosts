<?php

namespace App\Http\Controllers;

use App\Models\Votes;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class VotesController extends Controller
{
    public function GetUser(Request $request) {
        return $request->input('user');
    }

    public function ListAllVotes(Request $request) {
        return Votes::all();
    }
    
    public function ValidateRequest(Request $request) {
        $validator = Validator::make($request->all(), [
            'fk_id_post'=>'required | exists:post,id_post',
            'vote'=>'required | boolean'
        ]);

        return $this->ValidateVote($request, $validator);
    }

    public function ValidateVote(Request $request, $validator) {
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        return $this->CreateVote($request);
    }

    public function CreateVote(Request $request) {
        $user = $this->GetUser($request);
        $post = Post::find($request['fk_id_post']);

        $updateCreateVote = $this->UpdateCreateVote($request);
        $voteCount = $this->UpdateVoteCount($post);

        $vote['vote'] = $updateCreateVote;
        $vote['vote_count'] = $voteCount;

        return $vote;
    }

    private function UpdateCreateVote(Request $request) {
        $existingVote = $this->ExistingVote($request);

        if ($existingVote) {
            if ($existingVote->trashed()) {
                return $this->RestoreTrashedVote($request, $existingVote);
            }

            return $this->RestoreEliminateVote($request, $existingVote);
        }

        return $this->CreateNewVote($request);
    }

    private function ExistingVote(Request $request) {
        $user = $this->GetUser($request);

        return Votes::where('fk_id_user', $user['id'])
                        ->where('fk_id_post', $request['fk_id_post'])
                        ->withTrashed()
                        ->first();
    }

    private function RestoreTrashedVote(Request $request, $existingVote) {
            $existingVote->restore();
            if ($existingVote['vote'] != $request['vote']) {
                $existingVote -> vote = $request['vote'];
                $existingVote = $this->TransactionSave($existingVote);
            }

            return $existingVote['vote'];
    }

    private function RestoreEliminateVote(Request $request, $existingVote) {
        if ($existingVote['vote'] == $request['vote']) {
            $existingVote->delete();     
            return 2;          
        } else if ($existingVote['vote'] != $request['vote']) {
            $existingVote -> vote = $request['vote'];
            $existingVote = $this->TransactionSave($existingVote);
            return $existingVote['vote'];
        }
    }

    private function CreateNewVote(Request $request) {
        $user = $this->GetUser($request);

        $newVote = new Votes();
        $newVote -> fk_id_user = $user['id'];
        $newVote -> fk_id_post = $request->input('fk_id_post');
        $newVote -> vote = $request->input('vote');

        return $this->TransactionSave($newVote);
    }

    public function TransactionSave($vote) {        
        try {
            DB::raw('LOCK TABLE votes WRITE');
            DB::beginTransaction();
            $vote -> save();
            DB::commit();
            DB::raw('UNLOCK TABLES');
            return $vote;
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return $th->getMessage();
        }
        catch (\PDOException $th) {
            return response("Permission to DB denied",403);
        }
    }

    private function UpdateVoteCount(Post $post) {
        $votesCount = $post->votes()->where('vote', true)->count() - $post->votes()->where('vote', false)->count();
        $post->votes = $votesCount;
        $post->save();

        return $post->votes;
    }
}