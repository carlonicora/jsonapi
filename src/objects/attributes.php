<?php
namespace carlonicora\jsonapi\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\interfaces\importInterface;
use Exception;
use RuntimeException;

class attributes implements exportPreparationInterface, importInterface
{
    /** @var array */
    private array $attributes = [];

    /**
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function add(string $name, $value): void
    {
        if (array_key_exists($name, $this->attributes)){
            throw new RuntimeException('meta key already exising', 1);
        }

        $this->attributes[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function get(string $name) {
        if (!array_key_exists($name, $this->attributes)){
            throw new RuntimeException('meta key not exising', 2);

        }

        return $this->attributes[$name];
    }

    /**
     * @return array
     */
    public function prepare(): array
    {
        return $this->attributes;
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