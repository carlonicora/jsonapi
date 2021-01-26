<?php
namespace CarloNicora\JsonApi\Exception;

use CarloNicora\JsonApi\Abstracts\AbstractJsonApiException;

class AttributeException extends AbstractJsonApiException
{
    public const DUPLICATED_ATTRIBUTE=1000;
    public const ATTRIBUTE_NOT_FOUND=1001;

    /**
     * @param int $code
     * @return string
     */
    public function returnMessage(int $code): string
    {
        return match ($code) {
            self::DUPLICATED_ATTRIBUTE => 'Attribute already present.',
            self::ATTRIBUTE_NOT_FOUND => 'Attribute not found.',
            default => '',
        };
    }
}