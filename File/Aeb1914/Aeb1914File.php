<?php

namespace Softspring\AebSepaBundle\File\Aeb1914;

use Softspring\AebSepaBundle\File\AbstractAebFile;
use Softspring\AebSepaBundle\File\Aeb1914\Block\CreditorRegisters;
use Softspring\AebSepaBundle\File\Aeb1914\Block\RegistersTotalizerInterface;
use Softspring\AebSepaBundle\File\Aeb1914\Block\TotalPopulatorInterface;
use Softspring\AebSepaBundle\File\Aeb1914\Register\GeneralTotal;
use Softspring\AebSepaBundle\File\Aeb1914\Register\PresenterHeader;
use Symfony\Component\Validator\Constraints as Assert;

class Aeb1914File extends AbstractAebFile implements RegistersTotalizerInterface, TotalPopulatorInterface
{
    /**
     * @var PresenterHeader
     * @Assert\Valid()
     */
    public $presenterHeader;

    /**
     * @var CreditorRegisters[]
     * @Assert\Valid()
     */
    public $creditorRegisters = [];

    /**
     * @var GeneralTotal
     * @Assert\Valid()
     */
    public $generalTotal;

    /**
     * @inheritdoc
     */
    public function render($padChar = ' ', $registerGlue = '')
    {
        $render = $this->presenterHeader->render($padChar, $registerGlue);
        $render .= $registerGlue;

        foreach ($this->creditorRegisters as $register) {
            $render .= $register->render($padChar, $registerGlue);
            $render .= $registerGlue;
        }

        $render .= $this->generalTotal->render($padChar, $registerGlue);

        return $render;
    }

    public function populateTotalRegister()
    {
        if (! $this->generalTotal instanceof GeneralTotal) {
            $this->generalTotal = new GeneralTotal();
        }

        foreach ($this->creditorRegisters as $creditorRegister) {
            $creditorRegister->populateTotalRegister();
        }

        $this->generalTotal->registersNumber = $this->countRegisters();
        $this->generalTotal->totalImports = $this->totalImport();
        $this->generalTotal->registersTotalNumber = $this->countRegisters(true);
    }

    /**
     * @inheritdoc
     */
    public function countRegisters($withTotal = false)
    {
        $registers = $withTotal ? 2 : 1;

        foreach ($this->creditorRegisters as $creditorRegister) {
            $registers += $creditorRegister->countRegisters();
        }

        return $registers;
    }

    /**
     * @inheritdoc
     */
    public function totalImport()
    {
        $import = 0;

        foreach ($this->creditorRegisters as $creditorRegister) {
            $import += $creditorRegister->totalImport();
        }

        return $import;
    }

    /**
     * @inheritdoc
     */
    public function convertValues()
    {
        $this->presenterHeader->convertValues();

        foreach ($this->creditorRegisters as $creditorRegister) {
            $creditorRegister->convertValues();
        }

        $this->generalTotal->convertValues();
    }
}