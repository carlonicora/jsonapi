<?php
namespace carlonicora\jsonapi\traits;

use carlonicora\jsonapi\objects\links;
use carlonicora\jsonapi\objects\meta;

trait exportPreparationTrait
{
    /**
     * @param meta $meta
     * @param array $response
     * @param bool $overrideEmptyMeta
     */
    private function prepareMeta(meta $meta, array &$response, bool $overrideEmptyMeta=false) : void
    {
        if ($overrideEmptyMeta || 0 !== $meta->count()) {
            $response['meta'] = $meta->prepare();
        }
    }

    /**
     * @param links $links
     * @param array $response
     */
    private function prepareLinks(links $links, array &$response) : void
    {
        if (0 !== $links->count()) {
            $response['links'] = $links->prepare();
        }
    }
}