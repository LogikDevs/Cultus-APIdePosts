<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
 

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


/*
Route::post('CreatePost', 'App\Http\Controllers\PostController@CreatePost');
Route::get('ListAllPosts', 'App\Http\Controllers\PostController@ListAllPosts');
Route::get('ListOnePost', 'App\Http\Controllers\PostController@ListOnePost');
*/

//Route::get('/post/create', [PostController::class, 'CreatePost']);
Route::get('/post/listAll', [PostController::class, 'ListAllPosts']);
//Route::post('/post/create', [PostController::class, 'CreatePost']);
Route::post('/post/create', [PostController::class, 'PostCreate']);

