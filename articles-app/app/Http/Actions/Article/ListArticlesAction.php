<?php

declare(strict_types=1);

namespace App\Http\Actions\Article;

use App\Http\Repositories\ArticleRepository;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ListArticlesAction
{
    public function __construct(private ArticleRepository $articleRepository)
    {
    }

    public function __invoke(Request $request, Article $article): JsonResponse
    {
        return new JsonResponse($this->articleRepository->list(), 200);
    }
}
