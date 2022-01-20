<?php
namespace CarloNicora\JsonApi\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;

class ResourceLinkage implements ExportPreparationInterface
{
    /** @var ResourceObject[] */
    public array $resources = [];

    /** @var bool */
    protected bool $forceResourceList = false;

    public function add(ResourceObject $resource) : void
    {
        $this->resources[] = $resource;
    }

    /**
     * @param array|null $requiredFields
     * @return array
     */
    public function prepare(?array $requiredFields=null): array
    {
        $response = [];

        foreach ($this->resources as $resource) {
            if (!$this->forceResourceList && count($this->resources) === 1) {
                $response = $resource->prepareIdentifier();
            } else {
                $response[] = $resource->prepareIdentifier();
            }
        }

        return $response;
    }

    /**
     * @param bool $forceResourceList
     */
    public function forceResourceList(bool $forceResourceList): void
    {
        $this->forceResourceList = $forceResourceList;
    }
}