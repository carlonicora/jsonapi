<?php
namespace CarloNicora\JsonApi\Objects;

use CarloNicora\JsonApi\Exception\MetaException;
use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Interfaces\ImportInterface;
use Exception;

class Meta implements ExportPreparationInterface, ImportInterface
{
    /** @var array */
    private array $metaInformation;

    /**
     * Meta constructor.
     * @param array $metaInformation
     */
    public function __construct(array $metaInformation = [])
    {
        $this->metaInformation = $metaInformation;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function add(string $name, $value): void
    {
        if (array_key_exists($name, $this->metaInformation)){
            throw new MetaException($name, MetaException::DUPLICATED_META);
        }

        $this->metaInformation[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function get(string $name) {
        if (!array_key_exists($name, $this->metaInformation)){
            throw new MetaException($name, MetaException::META_NOT_FOUND);

        }

        return $this->metaInformation[$name];
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->metaInformation);
    }

    /**
     * @param array|null $requiredFields
     * @return array
     */
    public function prepare(?array $requiredFields=null): array
    {
        return $this->metaInformation;
    }

    /**
     * @param array $data
     * @param array|null $included
     * @throws Exception
     */
    public function importArray(array $data, array $included=null): void
    {
        foreach ($data as $metaKey=>$meta){
            $this->add($metaKey, $meta);
        }
    }
}