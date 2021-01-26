<?php
namespace CarloNicora\JsonApi\Exception;

use CarloNicora\JsonApi\Abstracts\AbstractJsonApiException;

class MetaException extends AbstractJsonApiException
{
    public const DUPLICATED_META=100000;
    public const META_NOT_FOUND=100001;

    /**
     * @param int $code
     * @return string
     */
    public function returnMessage(int $code): string
    {
        return match ($code) {
            self::DUPLICATED_META => 'meta already present.',
            self::META_NOT_FOUND => 'meta not foud.',
            default => '',
        };
    }
}