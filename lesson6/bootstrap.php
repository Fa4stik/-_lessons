<?php

use src\Container\DIContainer;
use src\Repositories\UserRepository;
use src\Repositories\UserRepositoryInterface;

require_once __DIR__ . '/vendor/autoload.php';

$container = new DIContainer;

$container->bind(PDO::class, new PDO('sqlite:' . __DIR__ . '/db/blog.sqlite'));
$container->bind(UserRepositoryInterface::class, UserRepository::class);

return $container;