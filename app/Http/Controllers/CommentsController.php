<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Post;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function GetUserLogged(Request $request) {
        return $request->input('user');
    }

    public function ListAll(Request $request) {
        return Comments::all();
    }

    public function ListOwnedComments(Request $request) {
        $user = $this->GetUserLogged($request);
        return Comments::where('fk_id_user', $user['id'])->get();
    }

    public function ListPostComments(Request $request, $id_post) {
        return Comments::where('fk_id_post', $id_post)->get();
    }

    private function UpdateCommentCount($postId) {
        $totalComments = Comments::where('fk_id_post', $postId)->count();
        $post = Post::find($postId);
        $post->comments = $totalComments;
        $post->save();
    }

    public function CreateComment(Request $request){
        //devuelve 200: ok
        $validator = Validator::make($request->all(), [
            'fk_id_post' => 'required | exists:post,id_post',
            'text' => 'required | max:255',
        ]);

        return $this->ValidateComment($request, $validator);
    }

    public function ValidateComment(Request $request, $validator) {
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        return $this->saveComment($request);
    }

    public function NewComment(Request $request, $user) {
        $newComment = new Comments();
        $newComment -> fk_id_user = $user['id'];
        $newComment -> fk_id_post = $request->input("fk_id_post");
        $newComment -> text = $request->input("text");

        return $newComment;
    }

    private function saveComment(Request $request) {
        $user = $this->GetUserLogged($request);
        $newComment = $this->NewComment($request, $user);
        $createdComment = $this->TransactionSaveComment($newComment);

        $this->UpdateCommentCount($request['fk_id_post']);

        return $this->ReturnNewComment($user, $createdComment);
    }

    public function ReturnNewComment($user, $createdComment) {
        $userData = [
            'name' => $user['name'],
            'surname' => $user['surname'],
            'profile_pic' => $user['profile_pic'],
        ];

        return [
            'comment' => $createdComment,
            'user' => $userData,
        ];
    }

    public function TransactionSaveComment($newComment) {        
        try {
            DB::raw('LOCK TABLE comments WRITE');
            DB::beginTransaction();
            $newComment -> save();
            DB::commit();
            DB::raw('UNLOCK TABLES');
            return $newComment;
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return $th->getMessage();
        }
        catch (\PDOException $th) {
            return response("Permission to DB denied",403);
        }
    }

    public function Delete(Request $request, $id_comment) {
        $user = $this->GetUserLogged($request);
        $comment = Comments::findOrFail($id_comment);
        
        if ($comment['fk_id_user'] == $user['id']) {
            return $this->TransactionDeleteComment($comment);
        }

        return response()->json(['Error' => 'No puedes eliminar este comentario ya que no eres el creador.'], 403);
    }

    private function TransactionDeleteComment($comment) {
        try {
            DB::raw('LOCK TABLE comments WRITE');
            DB::beginTransaction();
            $comment->delete();
            DB::commit();
            DB::raw('UNLOCK TABLES');
            $this->UpdateCommentCount($comment['fk_id_post']);
            return response()->json(['mensaje' => 'Eliminado con éxito.'], 200);
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return $th->getMessage();
        } catch (\PDOException $th) {
            return response("Permission to DB denied", 403);
        }
    }
}
