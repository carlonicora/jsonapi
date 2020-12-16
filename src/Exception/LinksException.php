<?php
namespace CarloNicora\JsonApi\Exception;

use CarloNicora\JsonApi\Abstracts\AbstractJsonApiException;

class LinksException extends AbstractJsonApiException
{
    public const DUPLICATED_LINK=10000;
    public const LINK_NOT_FOUND=10001;

    /**
     * @param int $code
     * @return string
     */
    public function returnMessage(int $code): string
    {
        switch ($code) {
            case self::DUPLICATED_LINK:
                return 'Link already present.';
            case self::LINK_NOT_FOUND:
                return 'Link not foud.';
            default:
                return '';
        }
    }
}