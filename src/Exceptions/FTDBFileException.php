<?php

namespace phpcodex\FTDB\Exceptions;

use \Exception;

class FTDBFileException extends Exception
{
    const FTDB_ERROR_STANDARD = 1;

    public function __construct(string $message, int $code = self::FTDB_ERROR_STANDARD, Exception $previous = null)
    {
        $this->message  = $message;
        $this->code     = $code;
        $this->previous = $previous;
    }
}