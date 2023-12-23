<?php

use app\Controller\PostController;
use app\Controller\UserController;
use app\Model\Post;
use app\Model\User;
use knot\Database\Database;

use function DI\autowire;
use function DI\create;
use function DI\get;

// return [
//   UserController::class => create(UserController::class),
//   User::class => create(User::class),
//   Database::class => create(Database::class),
//   Post::class => create()->constructor(get(Database::class)),
//   PostController::class => create()->constructor(get(Post::class)),
// ];

// $config = include 'DatabaseConfig.php';

return [
  UserController::class => autowire(),
  Database::class => autowire(),
  User::class => autowire(),
  Post::class => autowire(),
  PostController::class => autowire(),
];
