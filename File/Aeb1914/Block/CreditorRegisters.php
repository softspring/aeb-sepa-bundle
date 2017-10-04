<?php

namespace Softspring\AebSepaBundle\File\Aeb1914\Block;

use Softspring\AebSepaBundle\File\Aeb1914\Register\CreditorTotal;
use Softspring\AebSepaBundle\File\RenderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreditorRegisters implements RenderInterface, RegistersTotalizerInterface, TotalPopulatorInterface
{
    /**
     * @var CreditorDateRegisters[]
     * @Assert\Count(min="1")
     * @Assert\Valid()
     */
    public $registers = [];

    /**
     * @var CreditorTotal
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    public $total;

    /**
     * For populating total
     * @var string
     */
    public $creditorId;

    /**
     * @inheritdoc
     */
    public function render($padChar = ' ', $registerGlue = '')
    {
        $render = '';

        foreach ($this->registers as $register) {
            $render .= $register->render($padChar, $registerGlue);
            $render .= $registerGlue;
        }

        $render .= $this->total->render($padChar, $registerGlue);

        return $render;
    }

    /**
     * @inheritdoc
     */
    public function countRegisters()
    {
        $registers = 1; // total register

        foreach ($this->registers as $register) {
            $registers += $register->countRegisters();
        }

        return $registers;
    }

    /**
     * @inheritdoc
     */
    public function totalImport()
    {
        $import = 0;

        foreach ($this->registers as $register) {
            $import += $register->totalImport();
        }

        return $import;
    }

    /**
     * @inheritdoc
     */
    public function populateTotalRegister()
    {
        if (!$this->total instanceof CreditorTotal) {
            $this->total = new CreditorTotal();
        }

        $debtsNumber = 0;
        foreach ($this->registers as $register) {
            $register->populateTotalRegister();
            $debtsNumber += count($register->debts);
        }

        $this->total->creditorId = $this->creditorId;
        $this->total->debtsNumber = $debtsNumber;
        $this->total->registersNumber = $this->countRegisters();
        $this->total->totalImports = $this->totalImport();
    }


    /**
     * @inheritdoc
     */
    public function convertValues()
    {
        foreach ($this->registers as $register) {
            $register->convertValues();
        }

        $this->total->convertValues();
    }
}