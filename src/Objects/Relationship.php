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
     * @return array
     */
    public function prepare(): array
    {
        $response = [];

        $this->prepareMeta($this->meta, $response);
        $this->prepareLinks($this->links, $response);

        $response['data'] = $this->resourceLinkage->prepare();

        return $response;
    }

    /**
     * @param array $data
     * @param array|null $included
     * @throws Exception
     */
    public function importArray(array $data, array $included=null): void
    {
        if (array_key_exists('Meta', $data)) {
            $this->meta->importArray($data['Meta']);
        }

        if (array_key_exists('Links', $data)) {
            $this->links->importArray($data['Links']);
        }

        if (array_key_exists('data', $data)){
            if (array_key_exists('type', $data['data'])){
                $objectArray = $this->getResourceFromIncludedArray($data['data']['type'], $data['data']['id'], $included);
                $resourceIdentifierMeta = null;
                if (array_key_exists('Meta', $data['data'])){
                    $resourceIdentifierMeta = new Meta();
                    $resourceIdentifierMeta->importArray($data['data']['Meta']);
                }
                $this->resourceLinkage->add(new ResourceObject(null, null, $objectArray, $included, $resourceIdentifierMeta));
            } else {
                foreach ($data['data'] as $objectArray){
                    $resourceIdentifierMeta = null;
                    if (array_key_exists('Meta', $objectArray)){
                        $resourceIdentifierMeta = new Meta();
                        $resourceIdentifierMeta->importArray($objectArray['Meta']);
                    }
                    $objectArray = $this->getResourceFromIncludedArray($objectArray['type'], $objectArray['id'], $included);
                    $this->resourceLinkage->add(new ResourceObject(null, null, $objectArray, $included, $resourceIdentifierMeta));
                }
            }
        }
    }

    /**
     * @param string $type
     * @param string $id
     * @param array $included
     * @return array|null
     */
    public function getResourceFromIncludedArray(string $type, string $id, array $included) : ?array {
        foreach($included as $objectArray) {
            if ($objectArray['type'] === $type && $objectArray['id'] === $id){
                return $objectArray;
            }
        }

        return null;
    }
}