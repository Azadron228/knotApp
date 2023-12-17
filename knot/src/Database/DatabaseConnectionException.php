<?php

namespace knot\Database;

use Exception;

class DatabaseConnectionException extends Exception
{
    public function __construct($name, $message)
    {
        parent::__construct("Database connection error ({$name}): {$message}");
    }
}
