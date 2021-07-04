<?php

namespace App\Repositories;

use App\Models\Article;
use App\Repositories\Contracts\Repository;
use Illuminate\Support\Facades\Cache;

class ArticleRepository implements Repository
{
    public function all()
    {
        return Cache::remember('articles.all', 60 * 60, function () {
            return Article::all();
        });
    }
}
