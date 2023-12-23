<?php

namespace app\Controller;

use app\Model\Post;

class HelloController
{
  protected $post;
  public function __construct(Post $post) {
    $this->post = $post;
  }
  public function index()
  {
    $this->post->create();
    echo "Hello index";
  }
}
