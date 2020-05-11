<?php
namespace carlonicora\jsonapi\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\interfaces\importInterface;
use carlonicora\jsonapi\traits\exportPreparationTrait;
use Exception;

class relationship implements exportPreparationInterface, importInterface
{
    use exportPreparationTrait;

    /** @var meta  */
    public meta $meta;

    /** @var links  */
    public links $links;

    /** @var resourceLinkage  */
    public resourceLinkage $resourceLinkage;

    /**
     * relationship constructor.
     */
    public function __construct()
    {
        $this->meta = new meta();
        $this->links = new links();
        $this->resourceLinkage = new resourceLinkage();
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
        if (array_key_exists('meta', $data)) {
            $this->meta->importArray($data['meta']);
        }

        if (array_key_exists('links', $data)) {
            $this->links->importArray($data['links']);
        }

        if (array_key_exists('data', $data)){
            if (array_key_exists('type', $data['data'])){
                $objectArray = $this->getResourceFromIncludedArray($data['data']['type'], $data['data']['id'], $included);
                $resourceIdentifierMeta = null;
                if (array_key_exists('meta', $data['data'])){
                    $resourceIdentifierMeta = new meta();
                    $resourceIdentifierMeta->importArray($data['data']['meta']);
                }
                $this->resourceLinkage->add(new resourceObject(null, null, $objectArray, $included, $resourceIdentifierMeta));
            } else {
                foreach ($data['data'] as $objectArray){
                    $resourceIdentifierMeta = null;
                    if (array_key_exists('meta', $objectArray)){
                        $resourceIdentifierMeta = new meta();
                        $resourceIdentifierMeta->importArray($objectArray['meta']);
                    }
                    $objectArray = $this->getResourceFromIncludedArray($objectArray['type'], $objectArray['id'], $included);
                    $this->resourceLinkage->add(new resourceObject(null, null, $objectArray, $included, $resourceIdentifierMeta));
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