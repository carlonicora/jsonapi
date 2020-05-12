<?php
namespace CarloNicora\JsonApi\Interfaces;

interface ExportInterface
{
    /**
     * @return string
     */
    public function export() : string;
}