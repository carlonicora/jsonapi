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
        switch ($code) {
            case self::DUPLICATED_ATTRIBUTE:
                return 'Attribute already present.';
                break;
            case self::ATTRIBUTE_NOT_FOUND:
                return 'Attribute not foud.';
                break;
            default:
                return '';
                break;
        }
    }
}