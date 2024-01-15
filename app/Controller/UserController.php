<?php

namespace app\Controller;

use app\Model\User;
use app\Validator\UserValidator;
use DI\Container;
use knot\DB\Database;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController
{
  protected User $userModel;
  protected Container $container;
  protected Database $database;

  public function __construct(User $userModel, Database $database)
  {
    $this->userModel = $userModel;
    $this->database = $database;
  }

  public function createUser(RequestInterface $request, ResponseInterface $response)
  {
    $data = json_decode($request->getBody()->getContents(), true);
    $validator = new UserValidator($this->database);
    $isValid = $validator->validate($data);

    if ($isValid) {
      $validated = $validator->getValidatedData();
      $this->userModel->createUser(
        $validated['username'],
        $validated['email'],
        $validated['password']
      );

      $responseBody = json_encode(['message' => 'User created successfully']);
      $response = $response->withStatus(201) // Created status code
        ->withHeader('Content-Type', 'application/json')
        ->withBody($streamFactory->createStream($responseBody));
    } else {
      $errors = $validator->getErrors();

      $errors = $validator->getErrors();
      $responseBody = json_encode(['errors' => $errors]);
      $response = $response->withStatus(400) // Bad Request status code
        ->withHeader('Content-Type', 'application/json')
        ->withBody($streamFactory->createStream($responseBody));
    }
    return $response;
  }
}
