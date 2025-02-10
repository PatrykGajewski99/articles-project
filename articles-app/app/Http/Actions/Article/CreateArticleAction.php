<?php

declare(strict_types=1);

namespace App\Http\Actions\Article;

use App\Http\Requests\CreateArticleRequest;
use App\Models\Article;
use Illuminate\Http\JsonResponse;

final class CreateArticleAction
{
    public function __invoke(CreateArticleRequest $request): JsonResponse
    {
        $article = Article::query()->create($request->validated());

        return new JsonResponse($article, 201);
    }
}
