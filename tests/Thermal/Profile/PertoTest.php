<?php

namespace Thermal\Profile;

use Thermal\Model;
use Thermal\Printer;
use Thermal\PrinterTest;
use Thermal\Connection\Buffer;

class PertoTest extends \PHPUnit_Framework_TestCase
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
        $this->model = new Model('PertoPrinter');
        $this->connection = new Buffer();
        $this->model->getProfile()->setConnection($this->connection);
    }

    public function testBuzzer()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->buzzer();
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('buzzer_PertoPrinter', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }

    public function testCutter()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->cutter(Printer::CUT_FULL);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('cutter_PertoPrinter', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }
}
