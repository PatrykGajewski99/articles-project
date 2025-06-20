<?php

declare(strict_types=1);

namespace App\Http\Actions\Article;

use App\Http\Services\ArticlesCacheService;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class DeleteArticleAction
{
    public function __construct(private ArticlesCacheService $articlesCacheService)
    {
    }

    public function __invoke(Request $request, Article $article): JsonResponse
    {
        $this->articlesCacheService->forgetArticles();;

        $article->delete();

        return new JsonResponse([], 204);
    }
}
