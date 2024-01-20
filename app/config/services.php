<?php

use app\Controller\UserController;
use app\Model\User;
use knot\Auth\Auth;
use knot\Container\Container;
use knot\DB\Database;

return [
  Database::class => function () {
    $config = [
      'driver'   => 'sqlite',
      'database' => 'database.sqlite'
    ];

    return new Database($config);
  },
  //
  // User::class => function (Container $container) {
  //   return new User($container->get(Database::class));
  // },
  //
  // Auth::class => function () {
  //   return new Auth();
  // },
  //
  // UserController::class => function (Container $container) {
  //   return new UserController($container->get(User::class), $container->get(Database::class), $container->get(Auth::class));
  // },
];
