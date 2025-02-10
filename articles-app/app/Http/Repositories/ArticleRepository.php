<?php

namespace App\Http\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository
{
    public function list(): Collection
    {
        return Article::all();
    }
}
