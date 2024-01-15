<?php

namespace app\Controller;

use app\Model\Post;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;

class HelloController
{
  protected $post;
  public function __construct(Post $post) {
    $this->post = $post;
  }
  public function index(): Response
  {
    $response = new Response();
    $response->withStatus(300);
    return $response;
  }
}
