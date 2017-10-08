<?php
namespace Softspring\AebSepaBundle\Tests\File\Aeb1914\Register\PresenterHeader;

use Softspring\AebSepaBundle\File\Aeb1914\Register\PresenterHeader;
use Softspring\AebSepaBundle\Utils\Aeb1914;

class PresenterHeaderTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $fileId = Aeb1914::presentationFileId('12345678T');

        $presenterHeader = new PresenterHeader();
        $presenterHeader->creationDate = new \DateTime();
        $presenterHeader->presenterId = 'ES8200012345678T';
        $presenterHeader->presenterName = 'Juan Gonzalez Garcia';
        $presenterHeader->fileId = $fileId;
        $presenterHeader->receiverEntity = '1111';
        $presenterHeader->receiverOffice = '2222';

        $presenterHeader->convertValues();

        $render = $presenterHeader->render('-');

        // aeb register length
        $this->assertEquals(600, strlen($render));

        // 1 Código de registro OB Numérico 2 01-02
        $this->assertEquals('01', substr($render,0, 2));

        // 2 Versión del cuaderno OB Numérico 5 03-07
        $this->assertEquals('19143', substr($render,2, 5));

        // 3 Número de dato OB Numérico 3 08-10
        $this->assertEquals('001', substr($render,7, 3));

        // 4 Identificador del presentador OB Alfanumérico 35 11-45
        $this->assertEquals(str_pad('ES8200012345678T', 35, '-'), substr($render,10, 35));

        // 5 Nombre del presentador OB Alfanumérico 70 46-115
        $this->assertEquals(str_pad('Juan Gonzalez Garcia', 70, '-'), substr($render,45, 70));

        // 6 Fecha de creación del fichero OB Numérico 8 116-123
        $this->assertEquals(date('Ymd'), substr($render, 115, 8));

        // 7 Identificación del fichero OB Alfanumérico 35 124-158
        $this->assertEquals($fileId, substr($render, 123, 35));

        // 8 Entidad receptora OB Numérico 4 159-162
        $this->assertEquals('1111', substr($render, 158, 4));

        // 9 Oficina receptora OB Numérico 4 163-166
        $this->assertEquals('2222', substr($render, 162, 4));

        // 10 Libre OB Alfanumérico 434 167-600
        $this->assertEquals(str_pad('', 434, '-'), substr($render,166, 434));
    }
}
