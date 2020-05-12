<?php
namespace CarloNicora\JsonApi\Abstracts;

use Exception;
use Throwable;

abstract class AbstractJsonApiException extends Exception
{
    /**
     * AbstractJsonApiException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        $completeMessage = $this->returnMessage($code);

        if ($message !== '') {
            $completeMessage .= '('.$message.')';
        }

        parent::__construct($completeMessage, $code, $previous);
    }

    /**
     * @param int $code
     * @return string
     */
    abstract public function returnMessage(int $code) : string;
}