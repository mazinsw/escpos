<?php

namespace Thermal\Connection;

class BufferTest extends \PHPUnit\Framework\TestCase
{
    public function testOpen()
    {
        $buffer = new Buffer();
        $buffer->write('aaa');
        $this->assertEquals('aaa', $buffer->getBuffer());
        $buffer->open();
        $this->assertNull($buffer->getBuffer());
    }

    public function testWrite()
    {
        $buffer = new Buffer();
        $buffer->write('aaa');
        $this->assertEquals('aaa', $buffer->getBuffer());
    }

    public function testClose()
    {
        $buffer = new Buffer();
        $buffer->close();
        $this->assertNull($buffer->getBuffer());
    }

    public function testGetBuffer()
    {
        $buffer = new Buffer();
        $buffer->write('aaa');
        $this->assertEquals('aaa', $buffer->getBuffer());
    }
}
