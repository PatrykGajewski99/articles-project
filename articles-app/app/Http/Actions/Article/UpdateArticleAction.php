<?php

declare(strict_types=1);

namespace App\Http\Actions\Article;

use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\JsonResponse;

final class UpdateArticleAction
{
    public function __invoke(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        $article->update($request->validated());

        return new JsonResponse($article->refresh(), 200);
    }
}
