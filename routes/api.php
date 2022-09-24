<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProductController;

Route::get('hello', [ApiController::class, 'hellotest']);
Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);
Route::post('upload',  [ApiController::class, 'uploadImage'])->name('fileuploads');

Route::group(['middleware' => ['jwt.verify']], function() {

  //Route::middleware(['cors'])->group(function () {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::post('create', [ProductController::class, 'store']);
    Route::put('update/{product}',  [ProductController::class, 'update']);

   

    Route::delete('delete/{product}',  [ProductController::class, 'destroy']);
    Route::post('store-file', 'DocumentController@store');
  //});
});