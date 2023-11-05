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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;


class PostController extends Controller 
{
    public function GetUser(Request $request) {
        $tokenHeader = [ "Authorization" => $request -> header("Authorization")];
        return Http::withHeaders($tokenHeader)->get(getenv("API_AUTH_URL") . "/api/v1/validate");
    }

    private function GetUserData($fk_id_user) {
        $user = User::find($fk_id_user);
        return $user->only(['name', 'surname', 'profile_pic']);
    }

    public function GetPostDetails(Request $request, $postToList) {
        $tokenHeader = [ "Authorization" => $request -> header("Authorization")];
        $post['user'] = $this->GetUserData($postToList['fk_id_user']);
        $post['post'] = $postToList;
        $post['multimedia'] = $this->GetMultimedia($postToList['id_post']);
        $post['interests'] = $this->GetInterestsFromPost($postToList['id_post'], $tokenHeader);
        $post['comments'] = $this->GetComments($postToList['id_post']);

        return $post;
    }

    private function GetMultimedia($id_post) {
        return MultimediaPost::where('fk_id_post', $id_post)->get();
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

    public function ListAllPosts(Request $request) {
        return Post::all();
    }
    
    public function ListOnePost(Request $request, $id_post) {
        $postToList = Post::findOrFail($id_post);
        return $this->GetPostDetails($request, $postToList);
    }

    public function ListUserPosts(Request $request, $id_user) {
        $userPosts = Post::where('fk_id_user', $id_user)->get();
        foreach ($userPosts as $u) {
            $postData = $this->GetPostDetails($request, $u);
            $posts[] = $postData;
        }

        return $this->SortPostsByMostRecentDate($posts);
    }

    public function ListFollowed(Request $request) {
        $posts = [];
        $followeds = $this->GetFollowedUsers($request);
        foreach ($followeds as $f) {
            $userPosts = $this->ListUserPosts($request, $f['id_followed']);
            $posts = array_merge($posts, $userPosts);
        }

        return $this->SortPostsByMostRecentDate($posts);
    }

    public function ListInterested(Request $request) {
        $posts = [];
        $postsDetails = [];
        $user = $this->GetUser($request);

        $userInterests = $this->GetUserInterests($request, $user['id']);
        $posts = $this->GetInterestedCharacterizes($request, $userInterests);
        $postsDetails = $this->GetInterestedPostsDetails($request, $posts);

        return $postsDetails;
    }

    private function SortPostsByMostRecentDate($posts) {
        usort($posts, function($a, $b) {
            return strtotime($b['post']['date']) - strtotime($a['post']['date']);
        });

        return $posts;
    }

    private function GetFollowedUsers($request) {
        $route = getenv("API_AUTH_URL") . "/api/v1/followeds";
        $tokenHeader = [ "Authorization" => $request->header("Authorization")];
        $response = Http::withHeaders($tokenHeader)->get($route);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
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

    private function GetInterestedCharacterizes(Request $request, $userInterests) {
        foreach ($userInterests as $u) {
            $postInterested = $this->GetPostInterests($u['id_label']);
            $posts = $this->GetInterestedPosts($postInterested);
        }

        return $posts;
    }

    public function GetPostInterests($fk_id_label) {
        return Characterizes::where('fk_id_label', $fk_id_label)->get();
    }

    private function GetInterestedPosts($postInterested) {
        $posts = [];

        foreach ($postInterested as $p) {
            $post = $this->GetPostsFromMonthAgoToToday($p['fk_id_post']);
            if ($post) {
                $posts[] = $post;
            }
        }
        
        return $posts;
    }

    private function GetPostsFromMonthAgoToToday($id_post) {
        $month = now()->subDays(30);
        return Post::where('id_post', $id_post)
        ->where('created_at', '>=', $month)
        ->orderBy('votes', 'desc')
        ->first();
    }

    private function GetInterestedPostsDetails(Request $request, $posts) {
        foreach ($posts as $p) {
            $postDetails = $this->GetPostDetails($request, $p);
            $postsDetails[] = $postDetails;
        }

        return $postsDetails;
    }



    public function CreatePost(Request $request){
        $validator = Validator::make($request->all(), [
            'fk_id_event' => 'nullable | exists:events,id',
            'text' => 'required | max:500',
            'latitud' => 'nullable | numeric',
            'longitud' => 'nullable | numeric'
        ]);
        
        return $this->ValidatePost($request, $validator);
    }

    public function ValidatePost(Request $request, $validator){
        $user = $this->GetUser($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //return $this->ValidateEvent($request);
        $id_event = $request['fk_id_event'];
        $tokenHeader = [ "Authorization" => $request -> header("Authorization")];
        $ruta = getenv("API_EVENTS_URL") . "/api/v1/events/$id_event";
        dd(Http::withHeaders($tokenHeader)->get($ruta));
        //$event = Http::withHeaders($tokenHeader)->get($ruta);
        return $this->savePost($request, $user['id']);
    }


    public function ValidateEvent(Request $request){
        $id_event = $request['fk_id_event'];
        //return $id_event;
        $tokenHeader = [ "Authorization" => $request -> header("Authorization")];
        $ruta = getenv("API_EVENTS_URL") . "/api/v1/events/$id_event";
        $event = Http::withHeaders($tokenHeader)->get($ruta);
        return $event;
    }

    private function savePost(Request $request, $id_user) {
        $newPost = new Post();
        $newPost -> fk_id_user = $id_user;
        $newPost -> fk_id_event = $request->input('fk_id_event');
        $newPost -> text = $request->input('text');
        $newPost -> latitud = $request->input('latitud');
        $newPost -> longitud = $request->input('longitud');
        $newPost -> date = date('d-m-y H:i');
        
        return $this->TransactionSavePost($newPost);
    }

    public function TransactionSavePost($newPost) {        
        try {
            DB::raw('LOCK TABLE post WRITE');
            DB::beginTransaction();
            $newPost -> save();
            DB::commit();
            DB::raw('UNLOCK TABLES');
            return $newPost;
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return $th->getMessage();
        }
        catch (\PDOException $th) {
            return response("Permission to DB denied",403);
        }
    }

    public function Delete(Request $request, $id_post) {
        $user = $this->GetUser($request);
        $post = Post::findOrFail($id_post);
        
        if ($post['fk_id_user'] == $user['id']) {
            return $this->TransactionDeletePost($post);
        }

        return response()->json(['Error' => 'No puedes eliminar este post ya que no eres el creador.']);
    }

    private function TransactionDeletePost($post) {
        try {
            DB::raw('LOCK TABLE post WRITE');
            DB::beginTransaction();
            $post -> delete();
            DB::commit();
            DB::raw('UNLOCK TABLES');
            return response()->json(['mensaje' => 'Eliminado con éxito.'], 200);
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return $th->getMessage();
        } catch (\PDOException $th) {
            return response("Permission to DB denied", 403);
        }
    }
}
