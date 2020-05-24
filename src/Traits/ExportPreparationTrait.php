<?php
namespace CarloNicora\JsonApi\Traits;

use CarloNicora\JsonApi\Objects\Links;
use CarloNicora\JsonApi\Objects\Meta;

trait ExportPreparationTrait
{
    /**
     * @param Meta $meta
     * @param array $response
     * @param bool $overrideEmptyMeta
     */
    private function prepareMeta(Meta $meta, array &$response, bool $overrideEmptyMeta=false) : void
    {
        if ($overrideEmptyMeta || 0 !== $meta->count()) {
            $response['meta'] = $meta->prepare();
        }
    }

    /**
     * @param Links $links
     * @param array $response
     */
    private function prepareLinks(Links $links, array &$response) : void
    {
        if (0 !== $links->count()) {
            $response['links'] = $links->prepare();
        }
    }
}