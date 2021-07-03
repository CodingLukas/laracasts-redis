<?php

use App\Models\Article;
use Illuminate\Support\Facades\Redis;
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

Route::get('videos/{id}', function ($id) {
    $downloads = Redis::get('videos.' . $id . '.downloads');

//    $visits = Redis::incrBy('visits', 5);

    return view('welcome')->withDownloads($downloads);
});

Route::get('videos/{id}/download', function ($id) {
    Redis::incr('videos.' . $id . '.downloads');

    return back();
});

Route::get('/articles/trending', function () {
    $trending = Redis::zrevrange('trending_articles', 0, 2);

    $trending = Article::hydrate(
        array_map('json_decode', $trending)
    );

    return $trending;
});

Route::get('/articles/{article}', function (Article $article) {
    Redis::zincrby('trending_articles', 1, (string) $article);

//    Redis::zremrangebyrank('trending_articles', 0, -101);

    return $article;
});


