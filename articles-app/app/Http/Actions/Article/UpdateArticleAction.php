<?php

declare(strict_types=1);

namespace App\Http\Actions\Article;

use App\Http\Requests\UpdateArticleRequest;
use App\Http\Services\ArticlesCacheService;
use App\Models\Article;
use Illuminate\Http\JsonResponse;

final class UpdateArticleAction
{
    public function __construct(private ArticlesCacheService $articlesCacheService)
    {
    }

    public function __invoke(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        $this->articlesCacheService->forgetArticles();

        $article->update($request->validated());

        return new JsonResponse($article->refresh(), 200);
    }
}
