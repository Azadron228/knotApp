<?php

namespace app\Controller;

use app\Model\User;
use app\Validator\UserValidator;
use DI\Container;
use knot\Auth\Auth;
use knot\DB\Database;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController
{
  protected User $user;
  protected Database $database;

  public function __construct(User $user, Database $database)
  {
    $this->user = $user;
    $this->database = $database;
  }

  public function createUser(RequestInterface $request)
  {
    $data = json_decode($request->getBody()->getContents(), true);

    var_dump($data);

    $validator = new UserValidator($this->database);
    $isValid = $validator->validate($data);

    if ($isValid) {
      $user = $validator->getValidatedData();
      $this->user->createUser($user);
      Auth::login($user);
    } else {
      $errors = $validator->getErrors();
      echo $errors;
    }
  }

  public function logout(RequestInterface $request)
  {
    $data = json_decode($request->getBody()->getContents(), true);
    var_dump($data);
  }

  public function login(RequestInterface $request)
  {
    $data = json_decode($request->getBody()->getContents(), true);
    $user = $this->user->getUserByUsername($data['username']);

    $res = Auth::attempt($user, $data['password']);
    var_dump($res);
  }

  public function user(RequestInterface $requestInterface, $username)
  {

    // $data = json_decode($request->getBody()->getContents(), true);
    $res = $this->user->getUserByUsername($username);
    // var_dump($res);
    $a = Auth::isAuthenticated();
    var_dump($a);
  }
}
