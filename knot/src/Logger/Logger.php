<?php

namespace knot\Logger;

class Logger
{
  public const DEBUG = 7;
  public const INFO = 6;
  public const NOTICE = 5;
  public const WARNING = 4;
  public const ERROR = 3;
  public const CRITICAL = 2;
  public const ALERT = 1;
  public const EMERGENCY = 0;

  private const levels = [
    7 => 'Debug',
    6 => 'Info',
    5 => 'Notice',
    4 => 'Warning',
    3 => 'Error',
    2 => 'Critical',
    1 => 'Alert',
    0 => 'Emergency',
  ];

  private $logFilePath;

  public function __construct($logFilePath)
  {
    $this->logFilePath = $logFilePath;
  }

  public function debug($message, array $context = [])
  {
    return $this->log(self::DEBUG, $message, $context);
  }

  public function info($message, array $context = [])
  {
    return $this->log(self::DEBUG, $message, $context);
  }
  public function notice($message, array $context = [])
  {
    return $this->log(self::DEBUG, $message, $context);
  }
  public function error($message, array $context = [])
  {
    return $this->log(self::ERROR, $message, $context);
  }
  public function emergency($message, array $context = [])
  {
    return $this->log(self::EMERGENCY, $message, $context);
  }

  public function writer($message, $levelName)
  {
    file_put_contents($this->logFilePath, "[$levelName] $message" . PHP_EOL, FILE_APPEND);
  }

  public function log($level, $message, array $context = array())
  {
    $levelName = self::levels[$level];

    $message = $this->interpolate($message, $context);

    var_dump($message);

    return $this->writer($message, $levelName);
  }

  /**
   * Interpolates context values into the message placeholders.
   */
  function interpolate($message, array $context = array())
  {
    // build a replacement array with braces around the context keys
    $replace = array();
    foreach ($context as $key => $val) {
      // check that the value can be cast to string
      if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
        $replace['{' . $key . '}'] = $val;
      }
    }

    // interpolate replacement values into the message and return
    return strtr($message, $replace);
  }
}
