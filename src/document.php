<?php
namespace carlonicora\jsonapi;

use carlonicora\jsonapi\interfaces\exportInterface;
use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\interfaces\importInterface;
use carlonicora\jsonapi\objects\error;
use carlonicora\jsonapi\objects\meta;
use carlonicora\jsonapi\objects\resourceObject;
use carlonicora\jsonapi\traits\exportPreparationTrait;
use Exception;
use JsonException;

class document implements exportInterface, exportPreparationInterface, importInterface
{
    use exportPreparationTrait;

    /** @var meta  */
    public meta $meta;

    /** @var array|resourceObject[]  */
    public array $resources=[];

    /** @var resourceObject[]  */
    public array $included=[];

    /** @var error[]  */
    private array $errors = [];

    /** @var bool  */
    private bool $forceResourceList=false;

    /**
     * document constructor.
     * @param array|null $dataImport
     * @throws Exception
     */
    public function __construct(?array $dataImport=null)
    {
        $this->meta = new meta();

        if ($dataImport !== null){
            $this->importArray($dataImport);
        }
    }

    /**
     * @param resourceObject $resource
     */
    public function addResource(resourceObject $resource) : void
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
     * @param resourceObject $resource
     * @param bool $isPrimaryData
     */
    private function addIncluded(resourceObject $resource, bool $isPrimaryData=false) : void
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

        if (array_key_exists('meta', $data)) {
            $this->meta->importArray($data['meta']);
        }

        if (array_key_exists('data', $data)) {
            if (array_key_exists('type', $data['data'])){
                $this->resources[] = new resourceObject(null, null, $data['data'], $included);
            } else {
                foreach ($data['data'] as $resourceArray){
                    $this->resources[] = new resourceObject(null, null, $resourceArray, $included);
                }
            }
        }
    }
}