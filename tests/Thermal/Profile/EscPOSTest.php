<?php

namespace Thermal\Profile;

use Thermal\Model;

class EscPOSTest extends \PHPUnit_Framework_TestCase
{
    public function testTryPrintWithoutConnection()
    {
        $model = new Model('TM-T81');
        $profile = $model->getProfile();
        $this->setExpectedException('\Exception');
        $profile->buzzer();
    }

    public function testGetDefaultCodePage()
    {
        $model = new Model('TM-T81');
        $profile = $model->getProfile();
        $this->assertEquals('CP850', $profile->getDefaultCodePage());
    }

    public function testGetCodePages()
    {
        $model = new Model('TM-T81');
        $profile = $model->getProfile();
        $this->assertInternalType('array', $profile->getCodePages());
    }

    public function testSetInvalidFont()
    {
        $model = new Model('TM-T81');
        $profile = $model->getProfile();
        $this->setExpectedException('\Exception');
        $profile->setFont(['name' => 'Font D']);
    }

    public function testInvalidFontCapabilities()
    {
        $this->setExpectedException('\Exception');
        $model = new Model([
            'profile' => 'escpos',
            'codepage' => 'UTF-8',
            'columns' => 48,
            'fonts' => [
                [
                    'name' => 'Font A',
                    'columns' => 32
                ]
            ]
        ]);
    }
}
