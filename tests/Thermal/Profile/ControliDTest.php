<?php

namespace Thermal\Profile;

use Thermal\Model;
use Thermal\Printer;
use Thermal\PrinterTest;
use Thermal\Connection\Buffer;

class ControliDTest extends \PHPUnit\Framework\TestCase
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
        $this->model = new Model('PrintiD');
        $this->connection = new Buffer();
        $this->model->getProfile()->setConnection($this->connection);
    }

    public function testStyles()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->write('double width + height', Printer::STYLE_DOUBLE_WIDTH | Printer::STYLE_DOUBLE_HEIGHT);
        $profile->write('double width', Printer::STYLE_DOUBLE_WIDTH);
        $profile->write('double height', Printer::STYLE_DOUBLE_HEIGHT);
        $profile->write('bold italic', Printer::STYLE_BOLD + Printer::STYLE_ITALIC);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('styles_PrintiD', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testQrcode()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->qrcode('https://github.com/mazinsw/escpos', 4);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('qrcode_controlid', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }
}
