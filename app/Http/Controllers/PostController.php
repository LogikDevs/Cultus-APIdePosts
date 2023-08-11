<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Votes;
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
        $ruta = 'http://localhost:8000/api/v1/followers/'.$id_user;
        $response = Http::get($ruta);

        if ($response->successful()) {
            $followersData = $response->json();
            $posts = [];
            foreach ($followersData as $f) {
                $fk_id_user = $f['id_follower'];
                $followerPosts = $this->ListUserPosts($fk_id_user);

                $posts = array_merge($posts, $followerPosts->toArray());
            }
            return $posts;
        }
    }


/*    
    public function ListOne(user $user, $id){
        $User = user::findOrFail($id);
        $User -> homeland = $User->homeland()->get();
        $User -> residence = $User->residence()->get();
        $user->makeHidden(['password']);
        return $User;
   }
*/












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
                    // Obtén los datos del post y de las etiquetas de interés
                    $postData = $post->toArray();
                    $interestData = $interest; // Puedes ajustar esto según la estructura de los datos

                    // Agrega los datos de las etiquetas de interés al post
                    if (!isset($postsWithInterests[$postecito])) {
                        $postsWithInterests[$postecito] = [
                            'post' => $postData,
                            'interests' => [$interestData],
                        ];
                    } else {
                        $postsWithInterests[$postecito]['interests'][] = $interestData;
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
/*
                        public function GetMultimedia(Request $request) {
                            return MultimediaPost::findOrFail($request->input('fk_id_user'));
                        }
*/

                        public function GetInterests(Request $request) {
                            return MultimediaPost::findOrFail($request->input('fk_id_post'));
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
