<?php
namespace CarloNicora\JsonApi\Interfaces;

interface ExportPreparationInterface
{
    /**
     * @return array
     */
    public function prepare() : array;
}