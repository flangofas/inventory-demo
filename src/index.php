<?php

declare(strict_types=1);

use Inventory\ArticlesInMemoryPersistence;
use Inventory\GroupArticlesService;

require __DIR__ . '/../vendor/autoload.php';

$service = new GroupArticlesService;
$data = $service->handle(ArticlesInMemoryPersistence::fetchArticles());

dump($data);
