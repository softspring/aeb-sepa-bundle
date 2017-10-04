<?php

namespace Softspring\AebSepaBundle\File\Aeb1914\Register;

use Softspring\AebSepaBundle\File\RegisterInterface;
use Softspring\AebSepaBundle\Utils\AebFormat;
use Symfony\Component\Validator\Constraints as Assert;

class CreditorChargeDateTotal implements RegisterInterface
{
    /**
     * Código de registro
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="2")
     * @Assert\Type(type="digit")
     */
    protected $registerCode = '04';

    /**
     * Identificador del acreedor (AT-02)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="35")
     * @Assert\Type(type="alnum")
     */
    public $creditorId;

    /**
     * Fecha de cobro (AT-11)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="8")
     * @Assert\Type(type="digit")
     */
    public $chargeDate;

    /**
     * Total de importes
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="17")
     * @Assert\Type(type="digit")
     */
    public $totalImports;

    /**
     * Número de adeudos
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="8")
     * @Assert\Type(type="digit")
     */
    public $debtsNumber;

    /**
     * Total de registros
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="10")
     * @Assert\Type(type="digit")
     */
    public $registersNumber;

    /**
     * @inheritdoc
     */
    public function convertValues()
    {
        if ($this->chargeDate instanceof \DateTime) {
            $this->chargeDate = AebFormat::date($this->chargeDate);
        }

        if (!is_string($this->totalImports)) {
            $this->totalImports = AebFormat::float($this->totalImports);
        }

        $this->debtsNumber = "$this->debtsNumber";
        $this->registersNumber = "$this->registersNumber";
    }

    /**
     * @inheritdoc
     */
    public function render($padChar = ' ', $registerGlue = '')
    {
        $render = implode('', [
            str_pad($this->registerCode, 2, $padChar),

            str_pad($this->creditorId, 35, $padChar),
            str_pad($this->chargeDate, 8, $padChar),
            str_pad($this->totalImports, 17, $padChar),
            str_pad($this->debtsNumber, 8, $padChar),
            str_pad($this->registersNumber, 10, $padChar),

            str_pad('', 520, $padChar),
        ]);

        // $render .= strlen($render);

        return $render;
    }
}