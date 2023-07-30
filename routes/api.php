<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VotesController;
use App\Http\Controllers\CommentsController;
<<<<<<< Updated upstream
=======
use App\Http\Controllers\CharacterizesController;
use App\Http\Controllers\MultimediaPostController;
>>>>>>> Stashed changes
 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/post/listAll', [PostController::class, 'ListAllPosts']);
Route::get('/post/listUser/{id_user}', [PostController::class, 'ListUserPosts']);
Route::post('/post/create', [PostController::class, 'PostCreate']);
Route::post('/post/delete/{id_post}', [PostController::class, 'Delete']);


Route::get('/votes/listAll', [VotesController::class, 'ListAllVotes']);
Route::get('/votes/listOwned/{id_user}', [VotesController::class, 'ListOwnedVotes']);
Route::get('/votes/listPost/{id_post}', [VotesController::class, 'ListPostVotes']);
Route::post('/votes/create', [VotesController::class, 'CreateVote']);
Route::post('/votes/delete/{id_vote}', [VotesController::class, 'Delete']);













Route::get('/comments/listAll', [CommentsController::class, 'ListComments']);
Route::get('/comments/listOwned/{id_user}', [CommentsController::class, 'ListOwnedComments']);
Route::get('/comments/listPostComments/{id_post}', [CommentsController::class, 'ListPostComments']);
Route::post('/comments/create', [CommentsController::class, 'CreateComment']);
Route::post('/comments/delete/{id_vote}', [CommentsController::class, 'Delete']);