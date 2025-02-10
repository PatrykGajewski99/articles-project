<?php

declare(strict_types=1);

namespace App\Http\Actions\Article;

use App\Http\Services\ArticlesCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ListArticlesAction
{
    public function __construct(private ArticlesCacheService $articlesCacheService)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse($this->articlesCacheService->getAll(), 200);
    }
}
