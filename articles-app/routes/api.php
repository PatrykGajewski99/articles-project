<?php

use App\Http\Actions\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/articles')->group(function () {
    Route::post('/create', Article\CreateArticleAction::class);
    Route::get('/list', Article\ListArticlesAction::class);
    Route::prefix('/{article}')->group(function() {
        Route::get('/', Article\GetArticleAction::class);
        Route::delete('/', Article\DeleteArticleAction::class);
        Route::patch('/', Article\UpdateArticleAction::class);
    });
});
