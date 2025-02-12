<?php

declare(strict_types=1);

namespace App\Http\Actions\Article;

use App\Http\Requests\CreateArticleRequest;
use App\Http\Services\ArticlesCacheService;
use App\Models\Article;
use Illuminate\Http\JsonResponse;

final class CreateArticleAction
{
    public function __construct(private ArticlesCacheService $articlesCacheService)
    {
    }

    public function __invoke(CreateArticleRequest $request): JsonResponse
    {
        $this->articlesCacheService->forgetArticles();

        $article = Article::query()->create($request->validated());

        return new JsonResponse($article, 201);
    }
}
