<?php

namespace knot\Auth;

interface AuthInterface
{
  public static function bcrypt(string $password): string;
}
