<?php

namespace Thermal\Profile;

use Thermal\Model;
use Thermal\Printer;
use Thermal\PrinterTest;
use Thermal\Connection\Buffer;

class DieboldTest extends \PHPUnit_Framework_TestCase
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
        $this->model = new Model('IM453');
        $this->connection = new Buffer();
        $this->model->getProfile()->setConnection($this->connection);
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
        $this->setExpectedException('\Exception');
        $profile->drawer(Printer::DRAWER_2, 48, 96);
    }
}
