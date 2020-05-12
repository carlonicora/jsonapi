<?php
namespace CarloNicora\JsonApi\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;

class ResourceLinkage implements ExportPreparationInterface
{
    /** @var ResourceObject[] */
    public array $resources = [];

    public function add(ResourceObject $resource) : void
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