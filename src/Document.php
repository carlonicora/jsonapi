<?php
namespace CarloNicora\JsonApi;

use CarloNicora\JsonApi\Interfaces\ImportInterface;
use CarloNicora\JsonApi\Objects\error;
use CarloNicora\JsonApi\Objects\Links;
use CarloNicora\JsonApi\Objects\Meta;
use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\JsonApi\Traits\ExportPreparationTrait;
use Exception;
use JsonException;

class Document implements ImportInterface
{
    use ExportPreparationTrait;

    /** @var Meta  */
    public Meta $meta;

    /** @var Links  */
    public Links $links;

    /** @var array|ResourceObject[]  */
    public array $resources=[];

    /** @var ResourceObject[]  */
    public array $included=[];

    /** @var error[]  */
    private array $errors = [];

    /** @var bool  */
    private bool $forceResourceList=false;

    /**
     * Document constructor.
     * @param array|null $dataImport
     * @throws Exception
     */
    public function __construct(?array $dataImport=null)
    {
        $this->meta = new Meta();
        $this->links = new Links();

        if ($dataImport !== null){
            $this->importArray($dataImport);
        }
    }

    /**
     * @param ResourceObject $resource
     */
    public function addResource(ResourceObject $resource) : void
    {
        $this->resources[] = $resource;
    }

    /**
     * @param error $error
     */
    public function addError(error $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @param ResourceObject $resource
     * @param string $parentInclude
     * @param array|null $includedResourceTypes
     * @param bool $isPrimaryData
     */
    private function addIncluded(ResourceObject $resource, string $parentInclude='', array $includedResourceTypes=null, bool $isPrimaryData=false) : void
    {
        if (!$isPrimaryData) {
            $includedResourceFound = false;

            foreach ($this->included as $includedResource) {
                if ($resource->type === $includedResource->type && $resource->id === $includedResource->id) {
                    $includedResourceFound = true;
                    break;
                }
            }

            if (!$includedResourceFound) {
                $this->included[] = $resource;
            }
        }

        if (count($resource->relationships) > 0){
            foreach ($resource->relationships as $relationshipName=>$relationship){
                $newParentInclude = $parentInclude === '' ? $relationshipName : $parentInclude.'.'.$relationshipName;
                if ($includedResourceTypes === null || in_array($newParentInclude, $includedResourceTypes, true)) {
                    foreach ($relationship->resourceLinkage->resources as $childResource) {
                        $this->addIncluded($childResource, $newParentInclude, $includedResourceTypes);
                    }
                }
            }
        }
    }

    public function forceResourceList() : void
    {
        $this->forceResourceList = true;
    }

    /**
     * @param array|null $includedResourceTypes
     * @param array|null $requiredFields
     * @return array
     */
    public function prepare(?array $includedResourceTypes=null, ?array $requiredFields=null): array
    {
        foreach ($this->resources as $resource) {
            $this->addIncluded($resource, '', $includedResourceTypes, true);
        }

        $response = [];

        if (0 !== count($this->errors)) {
            $response['errors'] = [];

            foreach ($this->errors as $error) {
                $response['errors'][] = $error->prepare();
            }
        } elseif (0 !== count($this->resources)) {
            $response['data'] = [];
            foreach ($this->resources as $resource){
                if (!$this->forceResourceList && count($this->resources) === 1) {
                    $response['data'] = $resource->prepare($requiredFields[$resource->type] ?? null);
                } else {
                    $response['data'][] = $resource->prepare($requiredFields[$resource->type] ?? null);
                }
            }
        }

        $meta = $this->meta->prepare();
        if ([] === $response || [] !== $meta ){
            $this->prepareMeta($this->meta, $response, true);
        }

        $this->prepareLinks($this->links, $response);

        if (count($this->included) > 0) {
            $response['included'] = [];

            foreach ($this->included as $resource) {
                $response['included'][] = $resource->prepare($requiredFields[$resource->type] ?? null);
            }
        }

        return $response;
    }

    /**
     * @param array|null $includedResourceTypes
     * @param array|null $requiredFields
     * @return string
     * @throws JsonException
     */
    public function export(?array $includedResourceTypes=null, ?array $requiredFields=null): string
    {
        return json_encode($this->prepare($includedResourceTypes, $requiredFields), JSON_THROW_ON_ERROR);
    }

    /**
     * @param array $data
     * @param array|null $included
     * @throws Exception
     */
    public function importArray(array $data, array $included=null): void
    {
        if (array_key_exists('included', $data)){
            $included = $data['included'];
        }

        if (array_key_exists('meta', $data)) {
            $this->meta->importArray($data['meta']);
        }

        if (array_key_exists('Links', $data)) {
            $this->links->importArray($data['Links']);
        }

        if (array_key_exists('data', $data)) {
            if (array_key_exists('type', $data['data'])){
                $this->resources[] = new ResourceObject(null, null, $data['data'], $included);
            } else {
                foreach ($data['data'] as $resourceArray){
                    $this->resources[] = new ResourceObject(null, null, $resourceArray, $included);
                }
            }
        }
    }
}