<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
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

//route products
Route::resource('products', ProductController::class);

//route posts
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/getall', [PostController::class, 'getAll'])->name('getAll');
Route::post('/posts/store', [PostController::class, 'store'])->name('store');
