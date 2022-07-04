<?php

declare(strict_types=1);

use Inventory\ArticlesInMemoryPersistence;
use Inventory\GroupArticlesService;

require __DIR__ . '/../vendor/autoload.php';

// Rules
//as string
$rule = 'group';
//Or callable
//$rule = function(): string {
//    return uniqid();
//};

$service = new GroupArticlesService();
$data = $service->handle($rule, ArticlesInMemoryPersistence::fetchArticles());

dd($data);
