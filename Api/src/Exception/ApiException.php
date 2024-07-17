<?php

declare(strict_types=1);

namespace Src\Exception;

use Exception;

/**
 * Class ApiException
 * Base exception for all API exceptions
 */
class ApiException extends Exception
{
    public int $statusCode = 500;

    /**
     * @param string $message
     * @param int $statusCode
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(string $message = "", int $statusCode = 500, int $code = 0, Exception $previous = null)
    {
        $this->statusCode = $statusCode;
        parent::__construct($message, $code, $previous);
    }
}
