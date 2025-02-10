<?php

declare(strict_types=1);

namespace App\Http\Actions\Article;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GetArticleAction
{
    public function __invoke(Request $request, Article $article): JsonResponse
    {
        return new JsonResponse($article, 200);
    }
}
