<?php
namespace CarloNicora\JsonApi;

use CarloNicora\JsonApi\Interfaces\ExportInterface;
use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Interfaces\ImportInterface;
use CarloNicora\JsonApi\Objects\error;
use CarloNicora\JsonApi\Objects\Links;
use CarloNicora\JsonApi\Objects\Meta;
use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\JsonApi\Traits\ExportPreparationTrait;
use Exception;
use JsonException;

class Document implements ExportInterface, ExportPreparationInterface, ImportInterface
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
     *
     */
    private function buildIncluded() : void
    {
        foreach ($this->resources as $resource) {
            $this->addIncluded($resource, true);
        }
    }

    /**
     * @param ResourceObject $resource
     * @param bool $isPrimaryData
     */
    private function addIncluded(ResourceObject $resource, bool $isPrimaryData=false) : void
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
            foreach ($resource->relationships as $relationship){
                foreach ($relationship->resourceLinkage->resources as $childResource){
                    $this->addIncluded($childResource);
                }
            }
        }
    }

    public function forceResourceList() : void
    {
        $this->forceResourceList = true;
    }

    /**
     * @return array
     */
    public function prepare(): array
    {
        $this->buildIncluded();

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
                    $response['data'] = $resource->prepare();
                } else {
                    $response['data'][] = $resource->prepare();
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
                $response['included'][] = $resource->prepare();
            }
        }

        return $response;
    }

    /**
     * @return string
     * @throws JsonException
     */
    public function export(): string
    {
        return json_encode($this->prepare(), JSON_THROW_ON_ERROR);
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

        if (array_key_exists('Meta', $data)) {
            $this->meta->importArray($data['Meta']);
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