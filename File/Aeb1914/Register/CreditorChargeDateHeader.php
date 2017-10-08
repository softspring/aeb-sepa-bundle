<?php

namespace Softspring\AebSepaBundle\File\Aeb1914\Register;

use Softspring\AebSepaBundle\File\RegisterInterface;
use Softspring\AebSepaBundle\Utils\AebFormat;
use Softspring\AebSepaBundle\Validator\Constraints as AebAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CreditorChargeDateHeader implements RegisterInterface
{
    /**
     * Código de registro
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="2")
     * @Assert\Type(type="digit")
     */
    protected $registerCode = '02';

    /**
     * Versión del cuaderno
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="5")
     * @Assert\Type(type="digit")
     */
    public $aebBookVersion = '19143';

    /**
     * Número de dato
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="3")
     * @Assert\Type(type="digit")
     */
    protected $dataNumber = '002';

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
     * Nombre del acreedor (AT-03)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="70")
     * @AebAssert\AebSepaText()
     */
    public $creditorName;

    /**
     * Dirección acreedor (D1) (AT-05)
     *
     * @Assert\Length(max="50")
     * @AebAssert\AebSepaText()
     */
    public $creditorAddress1;

    /**
     * Dirección acreedor (D2) (AT-05)
     *
     * @Assert\Length(max="50")
     * @AebAssert\AebSepaText()
     */
    public $creditorAddress2;

    /**
     * Dirección acreedor (D3) (AT-05)
     *
     * @Assert\Length(max="40")
     * @AebAssert\AebSepaText()
     */
    public $creditorAddress3;

    /**
     * País del acreedor (AT-05)
     *
     * @Assert\Length(max="2")
     * @Assert\Country()
     */
    public $creditorCountry;

    /**
     * Cuenta del acreedor (AT-04)
     *
     * @Assert\Length(max="34")
     * @Assert\Iban()
     */
    public $creditorAccount;

    /**
     * @inheritdoc
     */
    public function convertValues()
    {
        if ($this->chargeDate instanceof \DateTime) {
            $this->chargeDate = AebFormat::date($this->chargeDate);
        }
    }

    /**
     * @inheritdoc
     */
    public function render($padChar = ' ', $registerGlue = '')
    {
        $render = implode('', [
            str_pad($this->registerCode, 2, $padChar),
            str_pad($this->aebBookVersion, 5, $padChar),
            str_pad($this->dataNumber, 3, $padChar),

            str_pad($this->creditorId, 35, $padChar),
            str_pad($this->chargeDate, 8, $padChar),
            str_pad($this->creditorName, 70, $padChar),
            str_pad($this->creditorAddress1, 50, $padChar),
            str_pad($this->creditorAddress2, 50, $padChar),
            str_pad($this->creditorAddress3, 40, $padChar),
            str_pad($this->creditorCountry, 2, $padChar),
            str_pad($this->creditorAccount, 34, $padChar),

            str_pad('', 301, $padChar),
        ]);

        // $render .= strlen($render);

        return $render;
    }
}