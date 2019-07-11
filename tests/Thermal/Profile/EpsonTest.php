<?php

namespace Thermal\Profile;

use Thermal\Model;
use Thermal\Connection\Buffer;
use Thermal\PrinterTest;

class EpsonTest extends \PHPUnit_Framework_TestCase
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

    public function testTryPrintWithoutConnectionTMT20()
    {
        $model = new Model('TM-T20');
        $profile = $model->getProfile();
        $this->setExpectedException('\Exception');
        $profile->buzzer();
    }

    public function testGetDefaultCodePageTMT20()
    {
        $profile = $this->model->getProfile();
        $this->assertEquals('CP850', $profile->getDefaultCodePage());
    }

    public function testGetCodePagesTMT20()
    {
        $profile = $this->model->getProfile();
        $this->assertInternalType('array', $profile->getCodePages());
    }

    public function testFontsTMT20()
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

    public function testSetInvalidFontTMT20()
    {
        $profile = $this->model->getProfile();
        $this->setExpectedException('\Exception');
        $profile->setFont(['name' => 'Font D']);
    }

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

    public function testQrcode()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->qrcode('https://github.com/mazinsw/escpos', 4);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('qrcode_epson', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testInvalidFontCapabilities()
    {
        $this->setExpectedException('\Exception');
        new Model([
            'profile' => 'epson',
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

    public function testUnknowModel()
    {
        $model = new Model([
            'brand' => 'Unknow',
            'profile' => 'Unknow',
            'codepage' => 'UTF-8',
            'columns' => 32,
            'fonts' => [
                [
                    'name' => 'Font A',
                    'columns' => 32
                ]
            ]
        ]);
        $this->assertEquals('Unknow Unknow', $model->getName());
    }
}
