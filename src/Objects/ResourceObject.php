<?php
namespace CarloNicora\JsonApi\Objects;

use CarloNicora\JsonApi\Document;
use CarloNicora\JsonApi\Interfaces\ImportInterface;
use Exception;
use RuntimeException;

class ResourceObject extends ResourceIdentifier implements ImportInterface
{
    /** @var Attributes  */
    public Attributes $attributes;

    /** @var Links  */
    public Links $links;

    /** @var Meta  */
    public Meta $meta;

    /** @var array|Relationship[] */
    public array $relationships=[];

    /**
     * ResourceObject constructor.
     * @param string|null $type
     * @param string|null $id
     * @param array|null $dataImport
     * @param array|null $included
     * @param Meta|null $resourceIdentifierMeta
     * @throws Exception
     */
    public function __construct(?string $type=null, ?string $id = null, ?array $dataImport=null, array $included=null, ?Meta $resourceIdentifierMeta=null)
    {
        $this->attributes = new Attributes();
        $this->links = new Links();
        $this->meta = new Meta();
        $this->resourceIdentifierMeta = new Meta();

        if ($type === null && $dataImport === null){
            throw new RuntimeException('Either type or the import data should be set', 1);
        }

        if ($dataImport !== null) {
            $this->importArray($dataImport, $included);
        } else {
            parent::__construct($type, $id);
        }

        if ($resourceIdentifierMeta !== null){
            $this->resourceIdentifierMeta = $resourceIdentifierMeta;
        }
    }

    /**
     * @return Document
     */
    public function generateDocumentFromResource(
    ): Document
    {
        $response = new Document();
        $response->addResource($this);
        return $response;
    }

    /**
     * @param string $relationshipKey
     * @return Relationship
     */
    public function relationship(string $relationshipKey) : Relationship
    {
        if (!array_key_exists($relationshipKey, $this->relationships)) {
            $this->relationships[$relationshipKey] = new Relationship();
        }

        return $this->relationships[$relationshipKey];
    }

    /**
     * @return array
     */
    public function prepareIdentifier() : array
    {
        return parent::prepare();
    }

    /**
     * @param array|null $requiredFields
     * @return array
     */
    public function prepare(?array $requiredFields=null): array
    {
        $response = parent::prepare();

        if (array_key_exists('meta', $response)){
            unset($response['meta']);
        }

        if (count($attributes = $this->attributes->prepare($requiredFields)) > 0){
            $response['attributes'] = $attributes;
        }

        $this->prepareMeta($this->meta, $response);
        $this->prepareLinks($this->links, $response);

        if (count($this->relationships) > 0) {
            $response['relationships'] = [];
            foreach ($this->relationships as $relationshipKey=>$relationship) {
                $response['relationships'][$relationshipKey] = $relationship->prepare();
            }
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
        if (array_key_exists('type', $data)){
            $this->type = $data['type'];
        } else {
            throw new RuntimeException('Invalid JsonApi document imported', 2);
        }

        if (array_key_exists('id', $data)) {
            $this->id = $data['id'];
        }

        if (array_key_exists('attributes', $data)) {
            $this->attributes->importArray($data['attributes']);
        }

        if (array_key_exists('meta', $data)) {
            $this->meta->importArray($data['meta']);
        }

        if (array_key_exists('links', $data)) {
            $this->links->importArray($data['links']);
        }

        if (array_key_exists('relationships', $data)) {
            foreach ($data['relationships'] as $relationshipKey=>$relationship){
                $readRelationship = $this->relationship($relationshipKey);
                if (empty($relationship['data'])) {
                    // A relationship can set only a link without data
                    $readRelationship->importArray($relationship, $included);
                } elseif (array_key_exists('type', $relationship['data'])) {
                    $readRelationship->importArray($relationship, $included);
                } else {
                    foreach ($relationship['data'] as $singleData){
                        $readRelationship->importArray(['data' => $singleData], $included);
                    }
                }
            }
        }
    }
}