<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VotesController;
 

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


//andan lol por las dudas
Route::get('/post/listAll', [PostController::class, 'ListAllPosts']);
Route::get('/post/listOwned/{id_post}', [PostController::class, 'ListOwnedPosts']);
Route::post('/post/create', [PostController::class, 'PostCreate']);
Route::post('/post/delete/{id_post}', [PostController::class, 'Delete']);


Route::get('/votes/listAll', [VotesController::class, 'ListAllVotes']);
///////////////////////////////////////////////////////////////////////////////////////////




Route::post('/votes/create', [VotesController::class, 'CreateVote']);

Route::get('/votes/change/{id_post}/{id_user}', [VotesController::class, 'EditVotesPost']);
//Route::middleware('auth:api')->post('/posts', 'PostController@CreatePost');
//Route::middleware('auth:api')->post('/posts', [PostController::class, 'CreatePost']);
Route::get('/votes/listOwned/{id_user}', [VotesController::class, 'ListOwnedVotes']);



Route::get('/votes/upvote', [VotesController::class, 'CreateUpvote']);
