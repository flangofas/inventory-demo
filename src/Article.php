<?php

declare(strict_types=1);

namespace Inventory;

use InvalidArgumentException;

final class Article
{
    private string $name;
    private int $group;
    private int $price;

    public function __construct(string $name, mixed $group, int $price)
    {
        if ($name === '') {
            throw new InvalidArgumentException('Article name cannot be empty');
        }

        $this->name = $name;
        $this->group = $group;
        $this->price = $price;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function group(): int
    {
        return $this->group;
    }

    public function price(): int
    {
        return $this->price;
    }

    public function isSmallerThan(Article $b, string $key): bool
    {
        return $this->{$key}() < $b->{$key}();
    }

    public function isGreaterThan(Article $b, string $key): bool
    {
        return $this->{$key}() > $b->{$key}();
    }
}
