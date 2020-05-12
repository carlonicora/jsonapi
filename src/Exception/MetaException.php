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
        switch ($code) {
            case self::DUPLICATED_META:
                return 'meta already present.';
                break;
            case self::META_NOT_FOUND:
                return 'meta not foud.';
                break;
            default:
                return '';
                break;
        }
    }
}