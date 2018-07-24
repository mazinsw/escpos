<?php

namespace Thermal\Profile;

use Thermal\Model;
use Thermal\Printer;
use Thermal\PrinterTest;
use Thermal\Connection\Buffer;

class DarumaTest extends \PHPUnit_Framework_TestCase
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
        $this->setExpectedException('\Exception');
        $profile->drawer(Printer::DRAWER_2, 48, 96);
    }

    public function testAlign()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->write('left', null, Printer::ALIGN_LEFT);
        $profile->write('center', null, Printer::ALIGN_CENTER);
        $profile->write('right', null, Printer::ALIGN_RIGHT);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('align_DR700', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testStyles()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->write('double width + height', Printer::STYLE_DOUBLE_WIDTH | Printer::STYLE_DOUBLE_HEIGHT, null);
        $profile->write('double width', Printer::STYLE_DOUBLE_WIDTH, null);
        $profile->write('double height', Printer::STYLE_DOUBLE_HEIGHT, null);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('styles_DR700', $this->connection->getBuffer()),
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
