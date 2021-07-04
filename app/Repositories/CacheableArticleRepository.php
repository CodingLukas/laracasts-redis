<?php

namespace App\Repositories;

use App\Models\Article;
use App\Repositories\Contracts\Repository;
use Illuminate\Support\Facades\Cache;

class CacheableArticleRepository implements Repository
{
    protected ArticleRepository $articles;

    public function __construct(ArticleRepository $articles)
    {
        $this->articles = $articles;
    }

    public function all()
    {
        return Cache::remember('articles.all', 60 * 60, function () {
            $this->articles->all();
        });
    }
}
