<?php

namespace Softspring\AebSepaBundle\File\Aeb1914\Register;

use Softspring\AebSepaBundle\File\RegisterInterface;
use Softspring\AebSepaBundle\Utils\AebFormat;
use Symfony\Component\Validator\Constraints as Assert;

class GeneralTotal implements RegisterInterface
{
    /**
     * Código de registro
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="2")
     * @Assert\Type(type="digit")
     */
    protected $registerCode = '99';

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
    public $registersNumber;

    /**
     * Total de registros
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="10")
     * @Assert\Type(type="digit")
     */
    public $registersTotalNumber;

    /**
     * @inheritdoc
     */
    public function convertValues()
    {
        if (!is_string($this->totalImports)) {
            $this->totalImports = AebFormat::float($this->totalImports);
        }

        $this->registersNumber = "$this->registersNumber";
        $this->registersTotalNumber = "$this->registersTotalNumber";
    }

    /**
     * @inheritdoc
     */
    public function render($padChar = ' ', $registerGlue = '')
    {
        $render = implode('', [
            str_pad($this->registerCode, 2, $padChar),

            str_pad($this->totalImports, 17, $padChar),
            str_pad($this->registersNumber, 8, $padChar),
            str_pad($this->registersTotalNumber, 10, $padChar),

            str_pad('', 563, $padChar),
        ]);

        // $render .= strlen($render);

        return $render;
    }
}