<?php
namespace CarloNicora\JsonApi\Interfaces;

interface ExportPreparationInterface
{
    /**
     * @param array|null $requiredFields
     * @return array
     */
    public function prepare(?array $requiredFields=null) : array;
}