<?php

namespace Thermal;

use Thermal\Connection\Buffer;

use Thermal\Graphics\Image;

class PrinterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Model
     *
     * @var Model
     */
    private $model;

    /**
     * Printer
     *
     * @var Printer
     */
    private $printer;

    /**
     * Connection
     *
     * @var Connection
     */
    private $connection;

    public static function getExpectedBuffer($name, $content)
    {
        $ext = '';
        if (!\preg_match('/\.\w+$/', $name)) {
            $ext = '.bin';
        }
        $filename = \dirname(__DIR__) . '/resources/' . $name . $ext;
        if (!\file_exists($filename)) {
            \file_put_contents($filename, $content);
        }
        return \file_get_contents($filename);
    }

    protected function setUp(): void
    {
        $this->model = new Model('MP-4200 TH');
        $this->connection = new Buffer();
        $this->printer = new Printer($this->model, $this->connection);
    }

    public function testSetCodePage()
    {
        $this->connection->clear();
        $this->printer->setCodePage('UTF-8');
        $this->assertEquals(
            self::getExpectedBuffer('set_utf-8_codepage', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->expectException('\Exception');
        $this->printer->setCodePage('BASE64');
    }

    public function testBuzzer()
    {
        $this->connection->clear();
        $this->printer->buzzer();
        $this->assertEquals(
            self::getExpectedBuffer('buzzer_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        // buzzer ESC/POS
        $columns = $this->printer->getColumns();
        $this->printer->setColumns(56);
        $this->connection->clear();
        $this->printer->buzzer();
        $this->assertEquals(
            self::getExpectedBuffer('buzzer_esc_pos_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->printer->setColumns($columns);
    }

    public function testCutter()
    {
        $this->connection->clear();
        $this->printer->cutter();
        $this->assertEquals(
            self::getExpectedBuffer('cutter_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testDrawer()
    {
        $this->connection->clear();
        $this->printer->drawer(Printer::DRAWER_1);
        $this->printer->drawer(Printer::DRAWER_2);
        $this->assertEquals(
            self::getExpectedBuffer('drawer_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->expectException('\Exception');
        $this->printer->drawer(3);
    }

    public function testDraw()
    {
        $this->connection->clear();
        $image_path = dirname(__DIR__) . '/resources/sample.jpg';
        $image = new Image($image_path);
        $this->printer->setAlignment(Printer::ALIGN_CENTER);
        $this->printer->draw($image);
        $this->printer->setAlignment(Printer::ALIGN_LEFT);

        $this->assertEquals(
            self::getExpectedBuffer('draw_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testQrcode()
    {
        $this->connection->clear();
        $this->printer->setAlignment(Printer::ALIGN_CENTER);
        $this->printer->qrcode('https://github.com/mazinsw/escpos');
        $this->printer->setAlignment(Printer::ALIGN_LEFT);

        $this->assertEquals(
            self::getExpectedBuffer('qrcode_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testDrawerESCPOS()
    {
        $columns = $this->printer->getColumns();
        $this->printer->setColumns(42);
        $this->connection->clear();
        $this->printer->drawer(Printer::DRAWER_1);
        $this->printer->drawer(Printer::DRAWER_2);
        $this->assertEquals(
            self::getExpectedBuffer('drawer_esc_pos_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->expectException('\Exception');
        try {
            $this->printer->drawer(3);
        } catch (\Exception $e) {
            $this->printer->setColumns($columns);
            throw $e;
        }
    }

    public function testWrite()
    {
        $this->connection->clear();
        $this->printer->write('test');
        $this->assertEquals(
            self::getExpectedBuffer('write_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->connection->clear();
        $this->printer->write('bold', Printer::STYLE_BOLD);
        $this->assertEquals(
            self::getExpectedBuffer('write_bold_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->connection->clear();
        $this->printer->write('condensed', Printer::STYLE_CONDENSED);
        $this->assertEquals(
            self::getExpectedBuffer('write_condensed_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->connection->clear();
        $this->printer->write('double height', Printer::STYLE_DOUBLE_HEIGHT);
        $this->assertEquals(
            self::getExpectedBuffer('write_double_height_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->connection->clear();
        $this->printer->write('expanded', Printer::STYLE_DOUBLE_WIDTH);
        $this->assertEquals(
            self::getExpectedBuffer('write_expanded_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->connection->clear();
        $this->printer->write('italic', Printer::STYLE_ITALIC);
        $this->assertEquals(
            self::getExpectedBuffer('write_italic_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->connection->clear();
        $this->printer->write('underline', Printer::STYLE_UNDERLINE);
        $this->assertEquals(
            self::getExpectedBuffer('write_underline_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->connection->clear();
        $this->printer->write(
            'all styles',
            Printer::STYLE_BOLD |
            Printer::STYLE_CONDENSED |
            Printer::STYLE_DOUBLE_HEIGHT |
            Printer::STYLE_DOUBLE_WIDTH |
            Printer::STYLE_ITALIC |
            Printer::STYLE_UNDERLINE
        );
        $this->assertEquals(
            self::getExpectedBuffer('write_all_styles_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->connection->clear();
        $this->printer->writeln('bold align right', Printer::STYLE_BOLD, Printer::ALIGN_RIGHT);
        $this->assertEquals(
            self::getExpectedBuffer('write_bold_align_right_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        $this->connection->clear();
        $this->printer->writeln('expanded align right', Printer::STYLE_DOUBLE_WIDTH, Printer::ALIGN_CENTER);
        $this->assertEquals(
            self::getExpectedBuffer('write_expanded_align_right_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        // bold ESC/POS
        $columns = $this->printer->getColumns();
        $this->printer->setColumns(56);
        $this->connection->clear();
        $this->printer->write('bold', Printer::STYLE_BOLD);
        $this->assertEquals(
            self::getExpectedBuffer('write_bold_esc_pos_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
        // restore columns
        $this->printer->setColumns($columns);
    }

    public function testWriteln()
    {
        $this->connection->clear();
        $this->printer->writeln('test');
        $this->assertEquals(
            self::getExpectedBuffer('writeln_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testFeed()
    {
        $this->connection->clear();
        $this->printer->feed(3);
        $this->assertEquals(
            self::getExpectedBuffer('feed_MP-4200_TH', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testColumns()
    {
        $columns = $this->printer->getColumns();
        $this->printer->setColumns(56);
        $this->assertEquals(56, $this->printer->getColumns());
        $this->printer->setColumns(42);
        $this->assertEquals(42, $this->printer->getColumns());
        $this->printer->setColumns($columns);
    }
}
