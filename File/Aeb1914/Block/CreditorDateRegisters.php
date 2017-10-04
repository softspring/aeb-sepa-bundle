<?php

namespace Softspring\AebSepaBundle\File\Aeb1914\Block;

use Softspring\AebSepaBundle\File\Aeb1914\Register\CreditorChargeDateHeader;
use Softspring\AebSepaBundle\File\Aeb1914\Register\CreditorChargeDateTotal;
use Softspring\AebSepaBundle\File\RenderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreditorDateRegisters implements RenderInterface, RegistersTotalizerInterface, TotalPopulatorInterface
{
    /**
     * @var CreditorChargeDateHeader
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    public $header;

    /**
     * @var DebtRegisters[]
     * @Assert\Count(min="1")
     * @Assert\Valid()
     */
    public $debts = [];

    /**
     * @var CreditorChargeDateTotal
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    public $total;

    /**
     * @inheritdoc
     */
    public function render($padChar = ' ', $registerGlue = '')
    {
        $render = $this->header->render($padChar, $registerGlue);

        $render .= $registerGlue;

        foreach ($this->debts as $register) {
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
        $registers = 2; // header + total

        foreach ($this->debts as $debt) {
            $registers += $debt->countRegisters();
        }

        return $registers;
    }

    /**
     * @inheritdoc
     */
    public function totalImport()
    {
        $import = 0;

        foreach ($this->debts as $debt) {
            $import += $debt->totalImport();
        }

        return $import;
    }

    public function populateTotalRegister()
    {
        if (!$this->total instanceof CreditorChargeDateTotal) {
            $this->total = new CreditorChargeDateTotal();
        }

        $this->total->creditorId = $this->header->creditorId;
        $this->total->chargeDate = $this->header->chargeDate;
        $this->total->debtsNumber = count($this->debts);
        $this->total->registersNumber = $this->countRegisters();
        $this->total->totalImports = $this->totalImport();
    }

    /**
     * @inheritdoc
     */
    public function convertValues()
    {
        $this->header->convertValues();

        foreach ($this->debts as $debt) {
            $debt->convertValues();
        }

        $this->total->convertValues();
    }
}