<?php

declare(strict_types=1);

namespace Inventory;

final class GroupArticlesService
{
    /** @var array<mixed> */
    private array $data = [];
    /** @var int[] */
    private const EXCEPTION_GROUPS = [0];

    /**
     * @param array<int, array<string, mixed>> $records
     * @return array<int,mixed>
     */
    public function handle(string|callable $rule, array $records): array
    {
        if (is_callable($rule)) {
            $rule = $rule();
        }

        $this->combineBy($rule, $this->buildArticles($records));

        $this->groupBy($rule);

        $this->sortBy('price', 'desc');

        return $this->data;
    }

    /**
     * @param array<int, array<string, mixed>> $records
     * @return array<int,Article>
     */
    private function buildArticles(array $records): array
    {
        return array_map(fn ($attributes) => new Article(...$attributes), $records);
    }

    /** @param array<int,Article> $articles */
    private function combineBy(string $rule, array $articles): void
    {
        foreach ($articles as $article) {
            $key = method_exists($article, $rule) ? $article->{$rule}() : $rule;

            $this->data[$key][] = $article;
        }
    }

    private function groupBy(string $rule): void
    {
        foreach (array_keys($this->data) as $key) {
            if ($rule === 'group' &&
                in_array($key, self::EXCEPTION_GROUPS, true)) {
                continue;
            }

            $this->data[$key] = [$this->summarize($this->data[$key])];
        }
    }

    private function sortBy(string $key, string $direction): void
    {
        $flatten = array_reduce($this->data, 'array_merge', []);

        $method = $direction === 'asc' ? 'isGreaterThan' : 'isSmallerThan';
        uasort($flatten, fn (Article $a, Article $b) => $a->{$method}($b, $key));

        $this->data = $flatten;
    }

    /** @param array<int,Article> $articles */
    private function summarize(array $articles): Article
    {
        $sumPrice = 0;
        $uniqueNames = [];
        $group = '';

        foreach ($articles as $article) {
            $name = $article->name();
            if (!in_array($name, $uniqueNames, true)) {
                $uniqueNames[] = $name;
            }

            $group = $article->group();
            $sumPrice += $article->price();
        }

        return new Article(implode(',', $uniqueNames), $group, $sumPrice);
    }
}
