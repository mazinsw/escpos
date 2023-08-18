<?php

namespace Thermal\Profile;

use Thermal\Model;
use Thermal\Printer;
use Thermal\PrinterTest;
use Thermal\Connection\Buffer;

class ElginTest extends \PHPUnit\Framework\TestCase
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
        $this->model = new Model('I9');
        $this->connection = new Buffer();
        $this->model->getProfile()->setConnection($this->connection);
    }

    public function testCutter()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->cutter(Printer::CUT_FULL);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('cutter_full_I9', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->connection->clear();
        $profile->cutter(Printer::CUT_PARTIAL);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('cutter_partial_I9', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testBuzzer()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->buzzer();
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('buzzer_I9', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testDrawer()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->drawer(Printer::DRAWER_1, 48, 240);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('drawer_I9', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->expectException('\Exception');
        $profile->drawer(Printer::DRAWER_2, 48, 96);
    }

    public function testQrcode()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->qrcode('https://github.com/mazinsw/escpos', 4);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('qrcode_elgin', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testStyles()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->write('double width + height', Printer::STYLE_DOUBLE_WIDTH | Printer::STYLE_DOUBLE_HEIGHT);
        $profile->write('double width', Printer::STYLE_DOUBLE_WIDTH);
        $profile->write('double height', Printer::STYLE_DOUBLE_HEIGHT);
        $profile->write('bold text', Printer::STYLE_BOLD);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('styles_I9', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }
}
