<?php

namespace Softspring\AebSepaBundle\File\Aeb1914\Block;

use Softspring\AebSepaBundle\File\Aeb1914\Register\IndividualDebtRequired1;
use Softspring\AebSepaBundle\File\RenderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class DebtRegisters implements RenderInterface, RegistersTotalizerInterface
{
    /**
     * @var IndividualDebtRequired1
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    public $required1;

    // public $optional2;

    // public $optional3;

    // public $optional4;

    // public $optional5;

    /**
     * @inheritdoc
     */
    public function render($padChar = ' ', $registerGlue = '')
    {
        // TODO render optional registers
        return $this->required1->render($padChar, $registerGlue);
    }

    /**
     * @inheritdoc
     */
    public function countRegisters()
    {
        // TODO count optional registers
        return 1;
    }

    /**
     * @inheritdoc
     */
    public function totalImport()
    {
        return $this->required1->debtImport;
    }


    /**
     * @inheritdoc
     */
    public function convertValues()
    {
        $this->required1->convertValues();
    }
}