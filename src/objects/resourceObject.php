<?php
namespace carlonicora\jsonapi\objects;

use carlonicora\jsonapi\interfaces\importInterface;
use carlonicora\jsonapi\traits\exportPreparationTrait;
use Exception;
use RuntimeException;

class resourceObject extends resourceIdentifier implements importInterface
{
    use exportPreparationTrait;

    /** @var attributes  */
    public attributes $attributes;

    /** @var links  */
    public links $links;

    /** @var array|relationship[] */
    public array $relationships=[];

    /**
     * resourceObject constructor.
     * @param string $type
     * @param string|null $id
     * @param array|null $dataImport
     * @param array|null $included
     * @throws Exception
     */
    public function __construct(?string $type=null, ?string $id = null, ?array $dataImport=null, array $included=null)
    {
        $this->attributes = new attributes();
        $this->links = new links();

        if ($type === null && $dataImport === null){
            throw new RuntimeException('Either type or the import data should be set', 1);
        }

        if ($dataImport !== null) {
            $this->meta = new meta();

            $this->importArray($dataImport, $included);
        } else {
            parent::__construct($type, $id);
        }
    }

    /**
     * @param string $relationshipKey
     * @return relationship
     */
    public function relationship(string $relationshipKey) : relationship
    {
        if (!array_key_exists($relationshipKey, $this->relationships)) {
            $this->relationships[$relationshipKey] = new relationship();
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
     * @return array
     */
    public function prepare(): array
    {
        $response = parent::prepare();

        $response['attributes'] = $this->attributes->prepare();

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
            throw new RuntimeException('Invalid jsonapi document imported', 2);
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
                $this->relationship($relationshipKey)->importArray($relationship, $included);
            }
        }
    }
}