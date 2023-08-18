<?php

namespace Thermal\Profile;

use Thermal\Model;
use Thermal\Printer;
use Thermal\PrinterTest;
use Thermal\Connection\Buffer;

class DieboldTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Thermal\Model
     */
    private $model;

    /**
     * @var \Thermal\Connection\Buffer
     */
    private $connection;

    protected function setUp(): void
    {
        $this->model = new Model('IM453');
        $this->connection = new Buffer();
        $this->model->getProfile()->setConnection($this->connection);
    }

    public function testBuzzer()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->buzzer();
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('buzzer_IM453_Diebold', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testBarcode()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->barcode('0123456789101', Printer::BARCODE_EAN13);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('barcode_IM453_Diebold', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testBarcodeCode128()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->barcode('0123456789101', Printer::BARCODE_CODE128);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('barcode_code128_IM453_Diebold', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testDrawer()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->drawer(Printer::DRAWER_1, 48, 96);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('drawer_IM453', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->expectException('\Exception');
        $profile->drawer(Printer::DRAWER_2, 48, 96);
    }
}
