<?php
namespace CarloNicora\JsonApi;

use CarloNicora\JsonApi\Interfaces\ImportInterface;
use CarloNicora\JsonApi\Objects\Error;
use CarloNicora\JsonApi\Objects\Links;
use CarloNicora\JsonApi\Objects\Meta;
use CarloNicora\JsonApi\Objects\ResourceObject;
use CarloNicora\JsonApi\Traits\ExportPreparationTrait;
use Exception;
use JsonException;
use RuntimeException;

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

    /** @var Error[]  */
    public array $errors = [];

    /** @var bool  */
    private bool $forceResourceList=false;

    /** @var array|null  */
    private ?array $includedResourceTypes=null;

    /** @var array|null  */
    private ?array $requiredFields=null;

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
     * @return string
     */
    public function getContentType(): string
    {
        return 'application/vnd.api+json';
    }

    /**
     * @param array $includedResourceTypes
     */
    public function setIncludedResourceTypes(array $includedResourceTypes) : void
    {
        $this->includedResourceTypes = [];

        foreach ($includedResourceTypes as $includedResourceType) {
            $this->includedResourceTypes[] = strtolower($includedResourceType);
        }
    }

    /**
     * @param array $requiredFields
     */
    public function setRequiredFields(array $requiredFields) : void
    {
        $this->requiredFields = [];

        foreach ($requiredFields as $requiredFieldKey => $requiredField) {
            $this->requiredFields[strtolower($requiredFieldKey)] = [];

            foreach ($requiredField as $value) {
                $this->requiredFields[strtolower($requiredFieldKey)][] = strtolower($value);
            }
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
     * @param array|ResourceObject[] $resourceList
     */
    public function addResourceList(array $resourceList) : void
    {
        $this->forceResourceList = true;

        foreach ($resourceList as $resource) {
            $this->addResource($resource);
        }
    }

    /**
     * @return ResourceObject
     */
    public function getSingleResource(
    ): ResourceObject
    {
        if (count($this->resources) === 0){
            throw new RuntimeException('The document does not contain any resource', 500);
        }

        if (count($this->resources) > 1){
            throw new RuntimeException('The document does not contain multiple resources', 500);
        }

        return $this->resources[0];
    }

    /**
     * @param Error $error
     */
    public function addError(Error $error): void
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
                if ($includedResourceTypes === null || in_array(strtolower($newParentInclude), $includedResourceTypes, true)) {
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
     * @return array
     */
    public function prepare(): array
    {
        foreach ($this->resources as $resource) {
            $this->addIncluded($resource, '', $this->includedResourceTypes, true);
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
                    $response['data'] = $resource->prepare($this->requiredFields[strtolower($resource->type)] ?? null);
                } else {
                    $response['data'][] = $resource->prepare($this->requiredFields[strtolower($resource->type)] ?? null);
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
                $response['included'][] = $resource->prepare($this->requiredFields[$resource->type] ?? null);
            }
        }

        return $response;
    }

    /**
     * @return string
     * @throws JsonException
     * @noinspection PhpDocRedundantThrowsInspection
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

        if (array_key_exists('links', $data)) {
            $this->links->importArray($data['links']);
        }

        if (array_key_exists('data', $data)) {
            if (array_key_exists('type', $data['data'])){
                $this->resources[] = new ResourceObject(null, null, $data['data'], $included);
            } else {
                foreach ($data['data'] as $resourceArray){
                    if (!is_array($resourceArray)) {
                        throw new RuntimeException('Invalid jsonapi document format');
                    }
                    $this->resources[] = new ResourceObject(null, null, $resourceArray, $included);
                }
            }
        }

        if (array_key_exists('errors', $data)) {
            foreach ($data['errors'] as $error) {
                $this->errors []= new Error(null, $error['status'] ?? null, $error['detail'] ?? null, $error['id'] ?? null, $error['code'] ?? null, $error['title'] ?? null);
            }
        }
    }
}