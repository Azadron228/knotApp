<?php

namespace app\Controller;

use app\Model\Post;

class PostController {

  protected $post;
  
  public function __construct(Post $post)
  {
    $this->post = $post;
  }

  public function hello() 
  {
    echo " PostController ";
  }
}
