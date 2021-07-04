<?php

use App\Models\Article;
use App\Repositories\ArticleRepository;
use App\Repositories\CacheableArticleRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Constraint\Callback;

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
    Redis::zincrby('trending_articles', 1, (string)$article);

//    Redis::zremrangebyrank('trending_articles', 0, -101);

    return $article;
});

//function remember (string $key, int $minutes, Closure $callback) {
//
//    if($value = Redis::get($key)){
//        return json_decode($value);
//    }
//
//    Redis::setex($key, $minutes, $value = $callback());
//
//    return $value;
//}

App::bind('Articles', function (){
   return new CacheableArticleRepository(new ArticleRepository());
});

Route::get('/', function (CacheableArticleRepository $articles) {

    return $articles->all();

//    remember('articles.all',  60 * 60, function(){
//       return Article::all();
//    });


//    $user2Stats = [
//        'favorites' => 50,
//        'watchLaters' => 90,
//        'completions' => 25
//    ];
//
//    Redis::hmset('user.2.stats', $user2Stats);


//    return Redis::hgetall('user.2.stats');

    /** put method serializes input */
//    Cache::put('foo', 'bar', 10);


//    return Cache::get('foo');
});

//Route::get('users/{id}/stats', function ($id) {
//
//    return Redis::hgetall('user.' . $id . '.stats');
//});

Route::get('favorite-video', function () {

    Redis::hincrby('user.' . 2 . '.stats', 'favorites', 1);

    return redirect('/');
});
