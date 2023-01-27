<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/create-post-job', [PostController::class, 'storeWithJob']);
Route::get('/create-post-event', [PostController::class, 'storeWithEvent']);
Route::get('/get-cat-1', [PostController::class, 'getCat1']);
Route::get('/get-cat-2', [PostController::class, 'getCat2']);
Route::get('/get-cat-4', [PostController::class, 'getCat4']);
Route::get('/check-cache', [PostController::class, 'checkCache']);
Route::get('/store-cache', [PostController::class, 'storeCache']);
Route::get('/change-word-4', [PostController::class, 'changeWord4']);
Route::get('/change-word-1/{keyword}', [PostController::class, 'changeWord1']);
Route::get('/change-word-2/{keyword}', [PostController::class, 'changeWord2']);
Route::get('/change-word-3/{keyword}', [PostController::class, 'changeWord3']);
Route::get('/change-word-5/{keyword}', [PostController::class, 'changeWord5']);
