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
    public function CreatePost(Request $request){
        $validation = [
            'fk_id_user' => 'required | exists:users,id',
            'fk_id_event' => 'nullable | exists:events,id_event',
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
        $newPost -> fk_id_event = $request->input('fk_id_event');
        $newPost -> text = $request->input('text');
        $newPost -> latitud = $request->input('latitud');
        $newPost -> longitud = $request->input('longitud');
        $newPost -> date = date('d-m-y H:i');
        $newPost -> save();
            
        return $newPost;
    }

    public function ListAllPosts(Request $request) {
        return Post::all();
    }
    
    public function ListOnePost($id_post) {
        return Post::findOrFail($id_post);
    }

    public function ListAllInterested($id_user) {
        $interestsData = $this->GetInterestsData($id_user);
        $postsWithInterests = [];

        foreach ($interestsData as $interest) {
            $int = $this->GetCharacterizedPosts($interest['id_label']);

            foreach ($int as $item) {
                $post = $this->GetPost($item['fk_id_post']);

                if ($post) {
                    $postData = $post->toArray();
                    $interestName = $interest['interest'];
                    $comments = $this->GetComments($post->id_post);
                    $multimedia = $this->GetMultimedia($post->id_post);

                    $this->AddPostToResult(
                        $postsWithInterests,
                        $post->id_post,
                        $postData,
                        $multimedia,
                        $post->user,
                        $comments,
                        $interestName
                    );
                }
            }
        }

        return array_values($postsWithInterests);
    }

    public function ListAllFollowed($id_user) {
        $followedsData = $this->GetFollowedsData($id_user);
        //$followedPosts = [];
        $followedPosts = collect();

        foreach ($followedsData as $f) {
            $fk_id_user = $f['id_followed'];
            $followedPosts = $followedPosts->merge($this->ListUserPosts($fk_id_user));
        }
        $posts = $this->ShowFollowedPosts($followedPosts);
        return $posts;
    }

    public function ListAllUserPosts($fk_id_user) {
        $userPosts = Post::where('fk_id_user', $fk_id_user)->get();

        foreach ($userPosts as &$post) {
            $user = $this->GetUser($post['fk_id_user']);
            $interests = $this->GetInterestsFromCharacterizes($post['id_post']);
            $multimedia = $this->GetMultimedia($post['id_post']);
            $comments = $this->GetComments($post['id_post']);
    
            $post['multimedia'] = $multimedia;
            $post['interests'] = $interests;
            $post['user'] = $user;
            $post['commentsPublished'] = $comments;
        }
    
        return $userPosts;
    }

    private function GetInterestsData($id_user) {
        $route = 'http://localhost:8000/api/v1/likes/user/' . $id_user . '/';
        $response = Http::get($route);

        if ($response->successful()) {
            return $response->json()['interests'];
        }

        return [];
    }

    private function GetCharacterizedPosts($fk_id_label) {
        return Characterizes::where('fk_id_label', $fk_id_label)->get();
    }

    private function GetPost($id_post) {
        return Post::find($id_post);
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

    private function GetMultimedia($id_post) {
/*
        return MultimediaPost::where('fk_id_post', $id_post)
            ->get()
            ->map(function ($multi) {
                return [
                    'multimediaLink' => $multi->multimediaLink
                ];
            });
*/
        return MultimediaPost::where('fk_id_post', $id_post)
            ->get()
            ->pluck('multimediaLink')
            ->toArray();
    }

    private function AddPostToResult(&$postsWithInterests, $postecito, $postData, $multimedia, $user, $comments, $interestName) {
        if (!isset($postsWithInterests[$postecito])) {
            $postsWithInterests[$postecito] = [
                'post' => $postData,
                'multimedia' => $multimedia,
                'interests' => [$interestName],
                'user' => $user->only(['name', 'surname', 'profile_pic']),
                'commentsPublished' => $comments,
            ];
        } else {
            $postsWithInterests[$postecito]['interests'][] = $interestName;
        }
    }

    private function GetFollowedsData($id_user) {
        $ruta = 'http://localhost:8000/api/v1/followeds/' . $id_user;
        $response = Http::get($ruta);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    private function ShowFollowedPosts($followedPosts) {
        $posts = [];

        foreach ($followedPosts as $post) {
            $user = $this->GetUser($post['fk_id_user']);
            $interests = $this->getInterestsFromCharacterizes($post['id_post']);
            $multimedia = $this->getMultimedia($post['id_post']);
            $comments = $this->getComments($post['id_post']);

            $post['multimedia'] = $multimedia;
            $post['interests'] = $interests;
            $post['user'] = $user;
            $post['commentsPublished'] = $comments;

            $posts[] = $post;
        }

        return $posts;
    }

    private function GetInterestsFromCharacterizes($id_post) {
        $all_labels = Characterizes::where('fk_id_post', $id_post)->get();
        $interests = [];

        foreach ($all_labels as $a) {
            $fk_id_label = $a['fk_id_label'];
            $ruta = 'http://localhost:8000/api/v1/interest/' . $fk_id_label;
            $response = Http::get($ruta);
            $interests[] = $response['interest'];
        }

        return $interests;
    }
        
    public function ListUserPosts($fk_id_user) {
        return Post::where('fk_id_user', $fk_id_user)->get();
    }

    private function GetUser($fk_id_user) {
        $user = User::find($fk_id_user);
        return $user->only(['name', 'surname', 'profile_pic']);
    }
































        

/*
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
*/



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



    public function Delete(Request $request, $id_post) {
        $post = Post::findOrFail($id_post);
        $post -> delete();
    }
}
