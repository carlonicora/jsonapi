<?php
namespace CarloNicora\JsonApi\Objects;

use BackedEnum;
use CarloNicora\JsonApi\Exception\AttributeException;
use CarloNicora\JsonApi\Interfaces\ExportPreparationInterface;
use CarloNicora\JsonApi\Interfaces\ImportInterface;
use Exception;

class Attributes implements ExportPreparationInterface, ImportInterface
{
    /** @var array */
    private array $attributes = [];

    /**
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function add(string $name, mixed $value): void
    {
        if (array_key_exists($name, $this->attributes)){
            throw new AttributeException($name, AttributeException::DUPLICATED_ATTRIBUTE);
        }

        $this->attributes[$name] = ($value instanceof BackedEnum) ? $value->value : $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * @param string $name
     */
    public function remove(string $name): void
    {
        if (array_key_exists($name, $this->attributes)){
            unset($this->attributes[$name]);
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function update(string $name, mixed $value): void
    {
        $this->attributes[$name] = ($value instanceof BackedEnum) ? $value->value : $value;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function get(string $name): mixed
    {
        if (!array_key_exists($name, $this->attributes)){
            throw new AttributeException($name, AttributeException::ATTRIBUTE_NOT_FOUND);
        }

        return $this->attributes[$name];
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->attributes);
    }

    /**
     * @param array|null $requiredFields
     * @return array
     */
    public function prepare(?array $requiredFields=null): array
    {
        if ($requiredFields === null) {
            return $this->attributes;
        }

        $response = [];

        foreach ($this->attributes as $attributeName => $attributeValue) {
            if (in_array(strtolower($attributeName), $requiredFields, true)){
                $response[$attributeName] = $attributeValue;
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
        foreach($data as $attributeKey=>$attributeValue) {
            $this->add($attributeKey, $attributeValue);
        }
    }
}