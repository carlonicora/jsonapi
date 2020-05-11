<?php
namespace carlonicora\jsonapi\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;

class resourceLinkage implements exportPreparationInterface
{
    /** @var resourceObject[] */
    public array $resources = [];

    public function add(resourceObject $resource) : void
    {
        $this->resources[] = $resource;
    }

    /**
     * @return array
     */
    public function prepare(): array
    {
        $response = [];

        foreach ($this->resources as $resource) {
            if (count($this->resources) === 1) {
                $response = $resource->prepareIdentifier();
            } else {
                $response[] = $resource->prepareIdentifier();
            }
        }

        return $response;
    }
}