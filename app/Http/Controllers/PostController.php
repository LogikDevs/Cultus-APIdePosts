<?php

namespace App\Http\Controllers;

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
    public function ListAllPosts(Request $request) {
        return Post::all();
    }
    
    public function ListOnePost($id_post) {
        return Post::findOrFail($id_post);
    }

    public function ListAllFollowed($id_user) {
/*
        $ruta = 'http://localhost:8000/api/v1/followeds/'.$id_user;
        $response = Http::get($ruta);

        if ($response->successful()) {
            $followersData = $response->json();
            $posts = [];
            foreach ($followersData as $f) {
                $fk_id_user = $f['id_followed'];
                $followedPosts = $this->ListUserPosts($fk_id_user);
                //$userData = $this->GetUser($fk_id_user);
                $followedPosts -> fk_id_user = $followedPosts->user()->get();

                $posts = array_merge($posts, $followedPosts->toArray());
            }
            return $posts;
        }
*/
        $ruta = 'http://localhost:8000/api/v1/followeds/'.$id_user;
        $response = Http::get($ruta);   

        if ($response->successful()) {
            $followersData = $response->json();
            $posts = [];

            foreach ($followersData as $f) {
                $fk_id_user = $f['id_followed'];
                $followerPosts = $this->ListUserPosts($fk_id_user);

                foreach ($followerPosts as $post) {
                    $user = User::find($post['fk_id_user']);
                    $post['user'] = $user->only(['name', 'surname']);
                    $post->makeHidden(['fk_id_user']);
                    $interests = [];


                    $id_post = $post['id_post'];
                    $all_labels = Characterizes::where('fk_id_post', $id_post)->get();
                    foreach ($all_labels as $a) {
                        $fk_id_label = $a['fk_id_label'];
                        $ruta = 'http://localhost:8000/api/v1/interest/'.$fk_id_label;
                        $response = Http::get($ruta);
                        $interests[] = $response['interest'];
                    }
                    $post['interests'] = $interests;


                    $comments = Comments::where('fk_id_post', $id_post)
                        ->with('user:id,name,surname')
                        ->get()
                        ->map(function ($comment) {
                            return [
                                'id_comment' => $comment->id_comment,
                                'text' => $comment->text,
                                'user' => $comment->user
                            ];
                        });

                    $post['commentsPublished'] = $comments;


                    $posts[] = $post;
                }
            }
            return $posts;
        }
    }
        


    public function ListAllInterested($id_user) {
        $route = 'http://localhost:8000/api/v1/likes/user/' . $id_user . '/';
        $response = Http::get($route);
    
        if ($response->successful()) {
            $responseData = $response->json();
            $interestsData = $responseData['interests'];
            $postsWithInterests = [];
    
            foreach ($interestsData as $interest) {
                $fk_id_interest = $interest['id_label'];
                $int = Characterizes::where('fk_id_label', $fk_id_interest)->get();
                foreach ($int as $item) {
                    $postecito = $item['fk_id_post'];
                    $post = Post::find($postecito);
                    if ($post) {
                        $postData = $post->toArray();
                        $interestName = $interest['interest'];
    
                        $comments = Comments::where('fk_id_post', $postecito)
                            ->with('user:id,name,surname')
                            ->get()
                            ->map(function ($comment) {
                                return [
                                    'id_comment' => $comment->id_comment,
                                    'text' => $comment->text,
                                    'user' => $comment->user
                                ];
                            });

                        $multimedia = MultimediaPost::where('fk_id_post', $postecito)
                            ->get()
                            ->map(function ($multi) {
                                return [
                                    'multimediaLink' => $multi->multimediaLink
                                ];
                            });

                        if (!isset($postsWithInterests[$postecito])) {
                            $postsWithInterests[$postecito] = [
                                'post' => $postData,
                                'multimedia' => $multimedia,
                                'user' => $post->user->only(['name', 'surname']),
                                'commentsPublished' => $comments,
                                'interests' => [$interestName],
                            ];
                        } else {
                            $postsWithInterests[$postecito]['interests'][] = $interestName;
                        }
                    }
                }
            }
    
            return array_values($postsWithInterests);
        }
    }

/*
    public function ListAllInterested($id_user) {
        $route = 'http://localhost:8000/api/v1/likes/user/' . $id_user . '/';
        $response = Http::get($route);
        
        if ($response->successful()) {
            $responseData = $response->json();
            $interestsData = $responseData['interests'];
            $posts = [];
            $postsEnteros = [];
        
            foreach ($interestsData as $interest) {
                $fk_id_interest = $interest['id_label'];
                $int = Characterizes::where('fk_id_label', $fk_id_interest)->get();
                foreach ($int as $item) {
                    $postecito = $item['fk_id_post'];
                    array_push($posts, $postecito);
                }
            }
            foreach ($posts as $postId) {
                $post = Post::find($postId);
                if ($post) {
                    array_push($postsEnteros, $post->toArray());
                }
            }
        
            return $postsEnteros;
        }
    }
*/

    public function ListUserPosts($fk_id_user) {
        return Post::where('fk_id_user', $fk_id_user)->get();
    }

    public function CreatePost(Request $request){
        $validation = [
            'fk_id_user' => 'required | exists:users,id',
            'text' => 'nullable | max:255',
            'latitud' => 'nullable | numeric',
            'longitud' => 'nullable | numeric'
        ];

        $request->validate($validation);
        return $this->savePost($request);
    }
    
    private function savePost(Request $request) {
        $newPost = new Post();
        $newPost -> fk_id_user = $request->input('fk_id_user');
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
