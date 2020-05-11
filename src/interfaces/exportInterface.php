<?php
namespace carlonicora\jsonapi\interfaces;

interface exportInterface
{
    /**
     * @return string
     */
    public function export() : string;
}