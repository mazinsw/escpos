<?php

namespace Thermal\Profile;

use Thermal\Model;
use Thermal\Printer;
use Thermal\PrinterTest;
use Thermal\Connection\Buffer;

class EscModeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Thermal\Model
     */
    private $model;

    /**
     * @var \Thermal\Connection\Buffer
     */
    private $connection;

    protected function setUp()
    {
        $this->model = new Model('TM-T20');
        $this->connection = new Buffer();
        $this->model->getProfile()->setConnection($this->connection);
    }

    public function testTryPrintWithoutConnection()
    {
        $model = new Model('TM-T20');
        $profile = $model->getProfile();
        $this->setExpectedException('\Exception');
        $profile->buzzer();
    }

    public function testGetDefaultCodePage()
    {
        $profile = $this->model->getProfile();
        $this->assertEquals('CP850', $profile->getDefaultCodePage());
    }

    public function testGetCodePages()
    {
        $profile = $this->model->getProfile();
        $this->assertInternalType('array', $profile->getCodePages());
    }

    public function testFonts()
    {
        $profile = $this->model->getProfile();
        $this->connection->clear();
        $profile->setColumns(64);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('font_B_TM-T20', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->connection->clear();
        $profile->setColumns(48);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('font_A_TM-T20', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testSetInvalidFont()
    {
        $profile = $this->model->getProfile();
        $this->setExpectedException('\Exception');
        $profile->setFont(['name' => 'Font D']);
    }

    public function testInvalidFontCapabilities()
    {
        $this->setExpectedException('\Exception');
        $model = new Model([
            'profile' => 'escmode',
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
