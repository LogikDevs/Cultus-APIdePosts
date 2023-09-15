<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Votes;
use App\Models\Comments;
use App\Models\Characterizes;
use App\Models\MultimediaPost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;


class PostController extends Controller 
{

    public function GetUserId(Request $request) {
        $tokenHeader = [ "Authorization" => $request -> header("Authorization")];
        $response = Http::withHeaders($tokenHeader)->get(getenv("API_AUTH_URL") . "/api/v1/validate");
        return $response['id'];
    }

    public function ListAllPosts(Request $request) {
        return Post::all();
    }
    
    public function ListOnePost($id_post) {
        return Post::findOrFail($id_post);
    }

    public function ListAllUserPosts(Request $request) {
        $tokenHeader = [ "Authorization" => $request -> header("Authorization")];
        $id_user = $this->GetUserId($request);
        $pos = [];
        $userPosts = [];
        $posts = Post::where('fk_id_user', $id_user)->get();
            foreach ($posts as $p) {
                $user = $this->GetUser($p['fk_id_user']);
                $multimedia = $this->GetMultimedia($p['id_post']);
                $interests = $this->GetInterestsFromPost($p['id_post'], $tokenHeader);
                $comments = $this->GetComments($p['id_post']);
        
                $pos['user'] = $user;
                $pos['post'] = $p;
                $pos['multimedia'] = $multimedia;
                $pos['interests'] = $interests;
                $pos['commentsPublished'] = $comments;
                array_push($userPosts, $pos);
            }
        
        return $userPosts;
    }

    public function ListFollowed(Request $request) {
        $tokenHeader = [ "Authorization" => $request -> header("Authorization")];
        $post = [];
        $followedPosts = [];
        $followeds = $this->GetFollowedsUsers($request);

        foreach ($followeds as $f) {
            $fk_id_user = $f['id_followed'];
            $userPosts = $this->GetUserPosts($fk_id_user);
            foreach ($userPosts as $p) {
                $user = $this->GetUser($p['fk_id_user']);
                $multimedia = $this->GetMultimedia($p['id_post']);
                $interests = $this->GetInterestsFromPost($p['id_post'], $tokenHeader);
                $comments = $this->GetComments($p['id_post']);
                
                $post['user'] = $user;
                $post['post'] = $p;
                $post['multimedia'] = $multimedia;
                $post['interests'] = $interests;
                $post['commentsPublished'] = $comments;
                array_push($followedPosts, $post);
            }
        }

        return $followedPosts;
    }

    public function ListInterested(Request $request) {
        $tokenHeader = [ "Authorization" => $request -> header("Authorization")];
        $response = Http::withHeaders($tokenHeader)->get(getenv("API_AUTH_URL") . "/api/v1/validate");
        $id_user = $response['id'];

        $post = [];
        $posts = [];
        $userInterests = $this->GetUserInterests($request, $id_user);

        foreach ($userInterests as $i) {
            $postInterests = $this->GetPostInterests($i['id_label']);
            foreach ($postInterests as $p) {
                $pos = $this->GetPost($p['fk_id_post']);
                $user = $this->GetUser($pos['fk_id_user']);
                $multimedia = $this->GetMultimedia($pos['id_post']);
                $interests = $this->GetInterestsFromPost($pos['id_post'], $tokenHeader);
                $comments = $this->GetComments($pos['id_post']);

                $post['user'] = $user;
                $post['post'] = $pos;
                $post['multimedia'] = $multimedia;
                $post['interests'] = $interests;
                $post['commentsPublished'] = $comments;
                
                array_push($posts, $post);
            }
        }
        return $posts;
    }

    private function GetFollowedsUsers($request) {
        $id_user = $this->GetUserId($request);
        $route = getenv("API_AUTH_URL") . "/api/v1/followeds/$id_user";
        $tokenHeader = [ "Authorization" => $request->header("Authorization")];
        $response = Http::withHeaders($tokenHeader)->get($route);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    public function GetUserPosts($fk_id_user) {
        return Post::where('fk_id_user', $fk_id_user)->get();
    }

    private function GetUser($fk_id_user) {
        $user = User::find($fk_id_user);
        return $user->only(['name', 'surname', 'profile_pic']);
    }
    
    private function GetMultimedia($id_post) {
        return MultimediaPost::where('fk_id_post', $id_post)
            ->get()
            ->pluck('multimediaLink')
            ->toArray();
    }

    public function GetInterestsFromPost($fk_id_post, $tokenHeader) {
        $postInterest = Characterizes::where('fk_id_post', $fk_id_post)->get();
        $interests = [];
        return $this->GetInterestName($postInterest, $interests, $tokenHeader);
    }

    public function GetInterestName($postInterest, $interests, $tokenHeader) {
        foreach ($postInterest as $a) {
            $fk_id_label = $a['fk_id_label'];
            $ruta = getenv("API_AUTH_URL") . "/api/v1/interest/$fk_id_label";
            $response = Http::withHeaders($tokenHeader)->get($ruta);
            $interests[] = $response['interest'];
        }

        return $interests;
    }

    private function GetComments($id_post) {
        return Comments::where('fk_id_post', $id_post)
            ->with('user:id,name,surname')
            ->get()
            ->map(function ($comment) {
                return [
                    'id_comment' => $comment->id_comment,
                    'text' => $comment->text,
                    'user' => $comment->user
                ];
            });
    }

    public function GetUserInterests(Request $request, $id_user) {
        $route = getenv("API_AUTH_URL") . "/api/v1/likes/user/$id_user";
        $tokenHeader = [ "Authorization" => $request->header("Authorization")];
        $response = Http::withHeaders($tokenHeader)->get($route);

        if ($response->successful()) {
            return $response->json()['interests'];
        }
        return [];
    }

    public function GetPostInterests($fk_id_label) {
        return Characterizes::where('fk_id_label', $fk_id_label)->get();
    }

    private function GetPost($id_post) {
        return Post::find($id_post);
    }



    public function CreatePost(Request $request){
        $id_user = $this->GetUserId($request);
        $validator = Validator::make($request->all(), [
            'fk_id_event' => 'nullable | exists:events,id',
            'text' => 'nullable | max:255',
            'latitud' => 'nullable | numeric',
            'longitud' => 'nullable | numeric'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        return $this->savePost($request, $id_user);
    }

    private function savePost(Request $request, $id_user) {
        $newPost = new Post();
        $newPost -> fk_id_user = $id_user;
        $newPost -> fk_id_event = $request->input('fk_id_event');
        $newPost -> text = $request->input('text');
        $newPost -> latitud = $request->input('latitud');
        $newPost -> longitud = $request->input('longitud');
        $newPost -> date = date('d-m-y H:i');
        $newPost -> save();
            
        return $newPost;
    }

    public function Delete(Request $request, $id_post) {
        $post = Post::findOrFail($id_post);
        $post -> delete();
    }
}
