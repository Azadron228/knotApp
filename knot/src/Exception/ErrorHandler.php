<?php

namespace knot\Exception;

use app\Kernel;

class ErrorHandler
{
  public function register()
  {
    set_error_handler(array(__CLASS__, 'handleErrors'));
  }

  public static function handleErrors($errno, $errstr = '', $errfile = '', $errline = '')
  {
    if (!($errno & error_reporting())) {
      return;
    }

    try {
      throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
    } catch (\Throwable $th) {
      (new Kernel())->getLogger()->error($th);
      exit(static::renderBody($th));
    }
  }

  protected static function renderBody($exception)
  {
    $title = 'Application Error';
    $code = $exception->getCode();
    $message = htmlspecialchars($exception->getMessage());
    $file = $exception->getFile();
    $line = $exception->getLine();

    $trace = str_replace(
      ['#', "\n"],
      ['<div>#', '</div>'],
      htmlspecialchars($exception->getTraceAsString())
    );
    $trace = str_replace(['): ', '</div>'], ['): <span style="color:#f4ae5d;">', '</span></div>'], $trace);
    $body = "<h1 style=\"color:#34be6d;\">$title</h1>";
    $body .= '<p>The application could not run because of the following error:</p>';
    $body .= '<h2>Details</h2>';
    $body .= sprintf('<div><strong>Type:</strong> %s</div>', get_class($exception));

    if ($code) {
      $body .= "<div><strong>Code:</strong> $code</div>";
    }

    if ($message) {
      $body .= "<div><strong>Message:</strong> $message</div>";
    }

    if ($file) {
      $body .= "<div><strong>File:</strong> $file</div>";
    }

    if ($line) {
      $body .= "<div><strong>Line:</strong> $line</div>";
    }

    if ($trace) {
      $body .= '<h2>Trace</h2>';
      $body .= "<pre style=\"padding:20px 30px 15px 30px;background:#003543;overflow-x:scroll;border-radius:10px;\">$trace</pre>";
    }

    return static::exceptionMarkup($title, $body);
  }

  protected static function exceptionMarkup($title, $body)
  {
    return "<html><head><title>$title</title><link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700;display=swap\"><style>body{background-color:rgb(0,30,38);color:white;margin:0;padding:50px;font:15px/14px DM Sans,sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}h2{margin-top:70px;}strong{color:#34be6d;display:inline-block;width:65px;}div{margin:15px 0px;}div strong{margin-right:40px;}</style></head><body>$body</body></html>";
  }
}
