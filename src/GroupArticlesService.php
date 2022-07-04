<?php

declare(strict_types=1);

namespace Inventory;

final class GroupArticlesService
{
    /** @var array<int,mixed> */
    private array $groups = [];
    /** @var int[] */
    private const EXCEPTION_GROUPS = [0];

    /**
     * @param array<int, array<string, mixed>> $records
     * @return array<int,mixed>
     */
    public function handle(array $records): array
    {
        $this->buildGroups($records);

        $this->summarizeGroups();

        $this->sortGroupsBy('price', 'desc');

        return $this->groups;
    }

    /** @param array<int,array<string,mixed>> $records */
    private function buildGroups(array $records): void
    {
        $articles = array_map(fn($attributes) => new Article(...$attributes), $records);

        foreach ($articles as $article) {
            $this->groups[$article->group()][] = $article;
        }
    }

    private function summarizeGroups(): void
    {
        foreach (array_keys($this->groups) as $group) {
            if (in_array($group, self::EXCEPTION_GROUPS, true)) {
                continue;
            }

            $this->groups[$group] = [$this->summarizeGroup($group, $this->groups[$group])];
        }
    }

    private function sortGroupsBy(string $key = 'group', string $direction = 'asc'): void
    {
        $flatten = array_reduce($this->groups, 'array_merge', []);

        $method = $direction === 'asc' ? 'isGreaterThan' : 'isSmallerThan';
        uasort($flatten, fn(Article $a, Article $b) => $a->{$method}($b));

        $this->groups = $flatten;
    }

    /** @param array<int,Article> $articles */
    private function summarizeGroup(int $group, array $articles): Article
    {
        $sumPrice = 0;
        $uniqueNames = [];

        foreach ($articles as $article) {
            $name = $article->name();
            if (!in_array($name, $uniqueNames, true)) {
                $uniqueNames[] = $name;
            }

            $sumPrice += $article->price();
        }

        return new Article(implode(',', $uniqueNames), $group, $sumPrice);
    }
}
