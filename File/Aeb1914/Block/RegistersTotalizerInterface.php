<?php

namespace Softspring\AebSepaBundle\File\Aeb1914\Block;

interface RegistersTotalizerInterface
{
    /**
     * @return int
     */
    public function countRegisters();

    /**
     * @return float
     */
    public function totalImport();
}