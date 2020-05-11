<?php
namespace carlonicora\jsonapi\interfaces;

interface importInterface
{
    /**
     * @param array $data
     * @param array|null $included
     */
    public function importArray(array $data, array $included=null) : void;
}