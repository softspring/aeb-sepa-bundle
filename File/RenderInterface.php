<?php

namespace Softspring\AebSepaBundle\File;

interface RenderInterface
{
    /**
     * @param string $padChar
     *
     * @param string $registerGlue
     *
     * @return string
     */
    public function render($padChar = ' ', $registerGlue = '');

    /**
     * Updates some values to final conversion
     * Do this before validation and render
     */
    public function convertValues();
}