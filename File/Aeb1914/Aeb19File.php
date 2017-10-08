<?php

namespace Softspring\AebSepaBundle\File\Aeb1914;

use Softspring\AebSepaBundle\File\AbstractAebFile;
use Softspring\AebSepaBundle\File\Aeb1914\Block\CreditorRegisters;
use Softspring\AebSepaBundle\File\Aeb1914\Block\RegistersTotalizerInterface;
use Softspring\AebSepaBundle\File\Aeb1914\Block\TotalPopulatorInterface;
use Softspring\AebSepaBundle\File\Aeb1914\Register\GeneralTotal;
use Softspring\AebSepaBundle\File\Aeb1914\Register\PresenterHeader;
use Symfony\Component\Validator\Constraints as Assert;

class Aeb19File extends AbstractAebFile implements RegistersTotalizerInterface, TotalPopulatorInterface
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
     * @var bool
     */
    protected $aeb1915Reduced = false;

    /**
     * Basic scheme or reduced deadline for submission
     * @param bool $aeb1915Reduced
     */
    public function setAeb1915Reduced($aeb1915Reduced = true)
    {
        $this->aeb1915Reduced = $aeb1915Reduced;
    }

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

        $this->generalTotal->registersNumber = $this->countDebtRegisters();
        $this->generalTotal->totalImports = $this->totalImport();
        $this->generalTotal->registersTotalNumber = $this->countRegisters();
    }

    /**
     * @inheritdoc
     */
    public function countRegisters()
    {
        $registers = 2;

        foreach ($this->creditorRegisters as $creditorRegister) {
            $registers += $creditorRegister->countRegisters();
        }

        return $registers;
    }

    /**
     * @inheritdoc
     */
    public function countDebtRegisters()
    {
        $registers = 0;

        foreach ($this->creditorRegisters as $creditorRegister) {
            $registers += $creditorRegister->total->debtsNumber;
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
        $aebBookVersion = $this->aeb1915Reduced ? '19154' : '19143';

        $this->presenterHeader->aebBookVersion = $aebBookVersion;
        $this->presenterHeader->convertValues();

        foreach ($this->creditorRegisters as $creditorRegister) {
            foreach ($creditorRegister->registers as $register) {
                $register->header->aebBookVersion = $aebBookVersion;
                foreach ($register->debts as $debt) {
                    $debt->required1->aebBookVersion = $aebBookVersion;
                }
            }

            $creditorRegister->convertValues();
        }

        $this->generalTotal->convertValues();
    }
}