<?php

namespace Thermal\Profile;

use Thermal\Model;
use Thermal\Printer;
use Thermal\PrinterTest;
use Thermal\Connection\Buffer;

class GenericTest extends \PHPUnit_Framework_TestCase
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
        $this->model = new Model('CMP-20');
        $this->connection = new Buffer();
        $this->model->getProfile()->setConnection($this->connection);
    }

    public function testStyles()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->write('double width + height', Printer::STYLE_DOUBLE_WIDTH | Printer::STYLE_DOUBLE_HEIGHT, null);
        $profile->write('double width', Printer::STYLE_DOUBLE_WIDTH, null);
        $profile->write('double height', Printer::STYLE_DOUBLE_HEIGHT, null);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('styles_CMP-20', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }
}
