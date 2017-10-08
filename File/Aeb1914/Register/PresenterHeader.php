<?php

namespace Softspring\AebSepaBundle\File\Aeb1914\Register;

use Softspring\AebSepaBundle\File\RegisterInterface;
use Softspring\AebSepaBundle\Utils\AebFormat;
use Softspring\AebSepaBundle\Validator\Constraints as AebAssert;
use Symfony\Component\Validator\Constraints as Assert;

class PresenterHeader implements RegisterInterface
{
    /**
     * Código de registro
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="2")
     * @Assert\Type(type="digit")
     */
    protected $registerCode = '01';

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
    protected $dataNumber = '001';

    /**
     * Identificador del presentador
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="35")
     * @Assert\Type(type="alnum")
     */
    public $presenterId;

    /**
     * Nombre del presentador
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="70")
     * @AebAssert\AebSepaText()
     */
    public $presenterName;

    /**
     * Fecha de creación del fichero
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="8")
     * @Assert\Type(type="digit")
     */
    public $creationDate;

    /**
     * Identificación del fichero
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="35")
     * @Assert\Type(type="alnum")
     */
    public $fileId;

    /**
     * Entidad receptora
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="4")
     * @Assert\Type(type="digit")
     */
    public $receiverEntity;

    /**
     * Oficina receptora
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="4")
     * @Assert\Type(type="digit")
     */
    public $receiverOffice;

    /**
     * @inheritdoc
     */
    public function convertValues()
    {
        if ($this->creationDate instanceof \DateTime) {
            $this->creationDate = AebFormat::date($this->creationDate);
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

            str_pad($this->presenterId, 35, $padChar),
            str_pad($this->presenterName, 70, $padChar),
            str_pad($this->creationDate, 8, $padChar),
            str_pad($this->fileId, 35, $padChar),
            str_pad($this->receiverEntity, 4, $padChar),
            str_pad($this->receiverOffice, 4, $padChar),

            str_pad('', 434, $padChar),
        ]);

        // $render .= strlen($render);

        return $render;
    }
}