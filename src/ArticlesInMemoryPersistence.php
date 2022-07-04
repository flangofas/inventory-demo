<?php

declare(strict_types=1);

namespace Inventory;

final class ArticlesInMemoryPersistence
{
    /** @return array<int,array<string,mixed>> */
    public static function fetchArticles(): array
    {
        return [
            ['name' => 'AA', 'group' => 1, 'price' => 10000],
            ['name' => 'BB', 'group' => 1, 'price' => 5000],
            ['name' => 'CC', 'group' => 2, 'price' => 7500],
            ['name' => 'AA', 'group' => 1, 'price' => 2000],
            ['name' => 'AA', 'group' => 0, 'price' => 10000],
            ['name' => 'BB', 'group' => 2, 'price' => 7500],
            ['name' => 'CC', 'group' => 2, 'price' => 8000],
            ['name' => 'AA', 'group' => 0, 'price' => 2000],
        ];
    }
}
