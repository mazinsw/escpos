<?php

namespace Thermal\Profile;

use Thermal\Model;
use Thermal\PrinterTest;
use Thermal\Connection\Buffer;

class DataregisTest extends \PHPUnit\Framework\TestCase
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
        $this->model = new Model('DT200');
        $this->connection = new Buffer();
        $this->model->getProfile()->setConnection($this->connection);
    }

    public function testBuzzer()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->buzzer();
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('buzzer_DT200_Dataregis', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }
}
