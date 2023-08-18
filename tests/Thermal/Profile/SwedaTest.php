<?php

namespace Thermal\Profile;

use Thermal\Model;
use Thermal\PrinterTest;
use Thermal\Connection\Buffer;

class SwedaTest extends \PHPUnit\Framework\TestCase
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
        $this->model = new Model('SI-300L');
        $this->connection = new Buffer();
        $this->model->getProfile()->setConnection($this->connection);
    }

    public function testBuzzer()
    {
        $this->connection->clear();
        $profile = $this->model->getProfile();
        $profile->buzzer();
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('buzzer_SI-300L_Sweda', $this->connection->getBuffer()),
            $this->connection->getBuffer()
        );
    }
}
