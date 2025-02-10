<?php

namespace App\Http\Services;

use App\Http\Repositories\ArticleRepository;
use Illuminate\Support\Facades\Cache;

class ArticlesCacheService
{
    public function __construct(private ArticleRepository $articleRepository)
    {
    }

    const CACHE_TTL = 60 * 10; // 10 minutes
    const CACHE_KEY = 'articles';

    public function getAll(): array
    {
        return Cache::store('redis')->remember(
                self::CACHE_KEY,
                self::CACHE_TTL,
                fn () => $this->getFreshArticles()
        );
    }

    public function forgetArticles(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    private function getFreshArticles(): array
    {
        return $this->articleRepository->list()
            ->toArray();
    }
}
