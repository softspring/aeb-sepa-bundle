<?php

namespace Softspring\AebSepaBundle\Tests\Utils\Aeb1914;

use Softspring\AebSepaBundle\Utils\Aeb1914;

class Aeb1914Test extends \PHPUnit_Framework_TestCase
{
    public function creditorIdProvider()
    {
        return [
            ['ES','000',' 00000001-R','ES4800000000001R'],
            ['ES','000','00000002W','ES2600000000002W'],
            ['ES','000','A12345678','ES53000A12345678'],
        ];
    }


    /**
     * @dataProvider creditorIdProvider
     *
     * @param string $country
     * @param string $suffix
     * @param string $creditorId
     * @param string $expectedId
     */
    public function testCreditorId($country, $suffix, $creditorId, $expectedId)
    {
        $resultId = Aeb1914::creditorId($country, $suffix, $creditorId);
        $this->assertEquals($expectedId, $resultId);
    }
}
