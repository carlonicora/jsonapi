<?php
namespace carlonicora\jsonapi\interfaces;

interface exportPreparationInterface
{
    /**
     * @return array
     */
    public function prepare() : array;
}