<?php
namespace CarloNicora\JsonApi\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;

class ResourceLinkage implements ExportPreparationInterface
{
    /** @var ResourceObject[] */
    public array $resources = [];

    /** @var bool */
    protected bool $forceResourceList = false;

    /**
     * @param ResourceObject $resource
     * @return void
     * @deprecated Use addResource as standard naming convention
     */
    public function add(ResourceObject $resource) : void
    {
        $this->addResource($resource);
    }

    /**
     * @param ResourceObject $resource
     * @return void
     */
    public function addResource(ResourceObject $resource) : void
    {
        $this->resources[] = $resource;
    }

    /**
     * @param array|ResourceObject[] $resourceList
     */
    public function addResourceList(array $resourceList) : void
    {
        $this->forceResourceList = true;
        $this->resources = array_merge($this->resources, $resourceList);
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