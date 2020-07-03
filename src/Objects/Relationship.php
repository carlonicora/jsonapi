<?php
namespace CarloNicora\JsonApi\Objects;

use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Interfaces\ImportInterface;
use CarloNicora\JsonApi\Traits\ExportPreparationTrait;
use Exception;

class Relationship implements ExportPreparationInterface, ImportInterface
{
    use ExportPreparationTrait;

    /** @var Meta  */
    public Meta $meta;

    /** @var Links  */
    public Links $links;

    /** @var ResourceLinkage  */
    public ResourceLinkage $resourceLinkage;

    /**
     * Relationship constructor.
     */
    public function __construct()
    {
        $this->meta = new Meta();
        $this->links = new Links();
        $this->resourceLinkage = new ResourceLinkage();
    }

    /**
     * @param array|null $requiredFields
     * @return array
     */
    public function prepare(?array $requiredFields=null): array
    {
        $response = [];

        $this->prepareMeta($this->meta, $response);
        $this->prepareLinks($this->links, $response);

        $response['data'] = $this->resourceLinkage->prepare();

        if (count($response) > 1 && $response['data'] === []){
            unset($response['data']);
        }

        return $response;
    }

    /**
     * @param array $data
     * @param array|null $included
     * @throws Exception
     */
    public function importArray(array $data, array $included=null): void
    {
        if (array_key_exists('meta', $data)) {
            $this->meta->importArray($data['meta']);
        }

        if (array_key_exists('links', $data)) {
            $this->links->importArray($data['links']);
        }

        if (array_key_exists('data', $data)){
            if (array_key_exists('type', $data['data'])){
                $resourceIdentifierMeta = null;
                if (array_key_exists('meta', $data['data'])){
                    $resourceIdentifierMeta = new Meta();
                    $resourceIdentifierMeta->importArray($data['data']['meta']);
                }
                $objectArray = $this->getResourceFromIncludedArray($data['data']['type'], $data['data']['id'] ?? null, $included);
                if ($objectArray === null){
                    $this->resourceLinkage->add(new ResourceObject($data['data']['type'], $data['data']['id'] ?? null, $data['data']));
                } else {
                    $this->resourceLinkage->add(new ResourceObject(null, null, $objectArray, $included, $resourceIdentifierMeta));
                }
            } else {
                foreach ($data['data'] as $objectArray){
                    $resourceIdentifierMeta = null;
                    if (array_key_exists('meta', $objectArray)){
                        $resourceIdentifierMeta = new Meta();
                        $resourceIdentifierMeta->importArray($objectArray['meta']);
                    }
                    $objectArray = $this->getResourceFromIncludedArray($objectArray['type'], $objectArray['id'] ?? null, $included);
                    if ($objectArray === null){
                        $this->resourceLinkage->add(new ResourceObject($objectArray['type'], $objectArray['id'] ?? null, $objectArray));
                    } else {
                        $this->resourceLinkage->add(new ResourceObject(null, null, $objectArray, $included, $resourceIdentifierMeta));
                    }

                }
            }
        }
    }

    /**
     * @param string $type
     * @param string|null $id
     * @param array|null $included
     * @return array|null
     */
    public function getResourceFromIncludedArray(string $type, string $id = null, ?array $included=null) : ?array {
        if ($id === null) {
            return null;
        }

        foreach($included ?? [] as $objectArray) {
            if ($objectArray['type'] === $type && $objectArray['id'] === $id){
                return $objectArray;
            }
        }

        return null;
    }
}