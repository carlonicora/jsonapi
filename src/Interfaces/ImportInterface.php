<?php
namespace CarloNicora\JsonApi\Interfaces;

interface ImportInterface
{
    /**
     * @param array $data
     * @param array|null $included
     */
    public function importArray(array $data, array $included=null) : void;
}