<?php
namespace carlonicora\jsonapi\objects;

use carlonicora\jsonapi\interfaces\exportPreparationInterface;
use carlonicora\jsonapi\interfaces\importInterface;
use Exception;
use RuntimeException;

class meta implements exportPreparationInterface, importInterface
{
    /** @var array */
    private array $metaInformation = [];

    /**
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
    public function add(string $name, $value): void
    {
        if (array_key_exists($name, $this->metaInformation)){
            throw new RuntimeException('meta key already exising', 1);
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
            throw new RuntimeException('meta key not exising', 2);

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
     * @return array
     */
    public function prepare(): array
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