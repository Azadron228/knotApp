<?php

class ErrorHandler
{

  public static function register()
  {
    set_error_handler(array(__CLASS__, 'handleError'));
  }


  public static function handleErrors($errno, $errstr = '', $errfile = '', $errline = '')
  {
    if (!($errno & error_reporting())) {
      return;
    }

    try {
      throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
    } catch (\Throwable $th) {
      $app = \Leaf\Config::get('app')['instance'];

      if ($app && $app->config('log.enabled')) {
        $app->logger()->error($th);
      }

      exit(static::renderBody($th));
    }
  }
}
