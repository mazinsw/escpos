<?php
namespace Thermal\Profile;

use Thermal\Model;
use Thermal\Printer;
use Thermal\PrinterTest;
use Thermal\Connection\Buffer;
use Thermal\Graphics\Image;

class DarumaTest extends \PHPUnit\Framework\TestCase
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
        $this->model = new Model('DR700');
        $this->connection = new Buffer();
        $this->model->getProfile()->setConnection($this->connection);
    }

    public function testDrawer()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->drawer(Printer::DRAWER_1, 48, 96);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('drawer_DR700', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->expectException('\Exception');
        $profile->drawer(Printer::DRAWER_2, 48, 96);
    }

    public function testAlign()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->writeln('left', 0, Printer::ALIGN_LEFT);
        $profile->writeln('center', 0, Printer::ALIGN_CENTER);
        $profile->writeln('right', 0, Printer::ALIGN_RIGHT);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('align_DR700', $this->connection->getBuffer()),
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
        $profile->write('bold italic', Printer::STYLE_BOLD + Printer::STYLE_ITALIC);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('styles_DR700', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testFeed()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->buzzer();
        $profile->feed(1);
        $profile->feed(4);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('feed_DR700', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testDraw()
    {
        $this->connection->clear();
        $image_path = dirname(dirname(__DIR__)) . '/resources/sample.jpg';
        $image = new Image($image_path);
        $profile = $this->model->getProfile();
        $profile->setAlignment(Printer::ALIGN_CENTER);
        $profile->draw($image);
        $profile->setAlignment(Printer::ALIGN_LEFT);

        $this->assertEquals(
            PrinterTest::getExpectedBuffer('draw_DR700', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testBarcode()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->barcode('0123456789101', Printer::BARCODE_EAN13);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('barcode_daruma', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testQrcode()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->qrcode('https://github.com/mazinsw/escpos', 4);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('qrcode_daruma', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testFonts()
    {
        $profile = $this->model->getProfile();
        $this->connection->clear();
        $profile->setColumns(52);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('font_B_DR700', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->connection->clear();
        $profile->setColumns(48);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('font_A_DR700', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }
}
