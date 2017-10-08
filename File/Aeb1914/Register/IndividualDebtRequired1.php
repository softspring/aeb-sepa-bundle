<?php

namespace Softspring\AebSepaBundle\File\Aeb1914\Register;

use Softspring\AebSepaBundle\File\RegisterInterface;
use Softspring\AebSepaBundle\Utils\AebFormat;
use Softspring\AebSepaBundle\Validator\Constraints as AebAssert;
use Symfony\Component\Validator\Constraints as Assert;

class IndividualDebtRequired1 implements RegisterInterface
{
    /**
     * Código de registro
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="2")
     * @Assert\Type(type="digit")
     */
    protected $registerCode = '03';

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
    protected $dataNumber = '003';

    /**
     * Referencia del adeudo (AT-10)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="35")
     * @Assert\Type(type="alnum")
     */
    public $debtReference;

    /**
     * Referencia única del mandato (AT-01)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="35")
     * @Assert\Type(type="alnum")
     */
    public $commandUniqueReference;

    /**
     * Secuencia del adeudo (AT-21)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="4")
     * @Assert\Type(type="alnum")
     */
    public $debtSequence;

    /**
     * Categoría de propósito (AT-59)
     *
     * @Assert\Length(max="4")
     * @AebAssert\AebSepaText()
     */
    public $purposeCategory;

    /**
     * Importe del adeudo (AT-06)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="11")
     * @Assert\Type(type="digit")
     */
    public $debtImport;

    /**
     * Fecha de firma del mandato (AT-25)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="8")
     * @Assert\Type(type="digit")
     */
    public $commandSignDate;

    /**
     * Entidad del deudor (AT-13)
     *
     * @Assert\Length(max="11")
     * @Assert\Type(type="alnum")
     */
    public $debtEntity;

    /**
     * Nombre del deudor (AT-14)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="70")
     * @AebAssert\AebSepaText()
     */
    public $debtorName;

    /**
     * Dirección deudor (D1) (AT-09)
     *
     * @Assert\Length(max="50")
     * @AebAssert\AebSepaText()
     */
    public $debtorAddress1;

    /**
     * Dirección deudor (D2) (AT-09)
     *
     * @Assert\Length(max="50")
     * @AebAssert\AebSepaText()
     */
    public $debtorAddress2;

    /**
     * Dirección deudor (D3) (AT-09)
     *
     * @Assert\Length(max="40")
     * @AebAssert\AebSepaText()
     */
    public $debtorAddress3;

    /**
     * País del deudor (AT-09)
     *
     * @Assert\Length(max="2")
     * @Assert\Country()
     */
    public $debtorCountry;

    /**
     * Tipo de Identificación del deudor
     *
     * @Assert\Length(max="1")
     * @Assert\Type(type="alnum")
     */
    public $debtorIdType;

    /**
     * Identificación del deudor (Código) (AT-27)
     *
     * @Assert\Length(max="36")
     * @Assert\Type(type="alnum")
     */
    public $debtorId;

    /**
     * Identificación del deudor emisor código (Otro)–(AT-27)
     *
     * @Assert\Length(max="35")
     * @Assert\Type(type="alnum")
     */
    public $debtorIdIssuerCode;

    /**
     * Identificador de la cuenta del deudor
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="1")
     * @Assert\Type(type="alnum")
     */
    public $debtorAccountId = 'A'; // A = IBAN

    /**
     * Cuenta del deudor (AT-07)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="34")
     * @Assert\Iban()
     */
    public $debtorAccount;

    /**
     * Propósito del adeudo (AT-58)
     *
     * @Assert\Length(max="4")
     * @Assert\Type(type="alnum")
     */
    public $debtPurpose;

    /**
     * Concepto (AT-22)
     *
     * @Assert\Length(max="140")
     * @AebAssert\AebSepaText()
     */
    public $concept;

    /**
     * @inheritdoc
     */
    public function convertValues()
    {
        if ($this->commandSignDate instanceof \DateTime) {
            $this->commandSignDate = AebFormat::date($this->commandSignDate);
        }

        if (!is_string($this->debtImport)) {
            $this->debtImport = AebFormat::float($this->debtImport);
        }

        $this->debtorName = AebFormat::text($this->debtorName);
        $this->debtorAddress1 = $this->debtorAddress1 !== null ? AebFormat::text($this->debtorAddress1) : null;
        $this->debtorAddress2 = $this->debtorAddress2 !== null ? AebFormat::text($this->debtorAddress2) : null;
        $this->debtorAddress3 = $this->debtorAddress3 !== null ? AebFormat::text($this->debtorAddress3) : null;
        $this->concept = AebFormat::text($this->concept);
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

            str_pad($this->debtReference, 35, $padChar),
            str_pad($this->commandUniqueReference, 35, $padChar),
            str_pad($this->debtSequence, 4, $padChar),
            str_pad($this->purposeCategory, 4, $padChar),
            str_pad($this->debtImport, 11, $padChar),
            str_pad($this->commandSignDate, 8, $padChar),
            str_pad($this->debtEntity, 11, $padChar),
            str_pad($this->debtorName, 70, $padChar),
            str_pad($this->debtorAddress1, 50, $padChar),
            str_pad($this->debtorAddress2, 50, $padChar),
            str_pad($this->debtorAddress3, 40, $padChar),
            str_pad($this->debtorCountry, 2, $padChar),
            str_pad($this->debtorIdType, 1, $padChar),
            str_pad($this->debtorId, 36, $padChar),
            str_pad($this->debtorIdIssuerCode, 35, $padChar),
            str_pad($this->debtorAccountId, 1, $padChar),
            str_pad($this->debtorAccount, 34, $padChar),
            str_pad($this->debtPurpose, 4, $padChar),
            str_pad($this->concept, 140, $padChar),

            str_pad('', 19, $padChar),
        ]);

        // $render .= strlen($render);

        return $render;
    }
}