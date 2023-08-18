<?php

namespace Thermal\Profile;

use Thermal\Model;
use Thermal\Connection\Buffer;
use Thermal\PrinterTest;

class BematechTest extends \PHPUnit\Framework\TestCase
{
    public function testGetDefaultCodePage()
    {
        $model = new Model('MP-4200 TH');
        $profile = $model->getProfile();
        $this->assertEquals('CP850', $profile->getDefaultCodePage());
    }

    public function testGetCodePages()
    {
        $model = new Model('MP-4200 TH');
        $profile = $model->getProfile();
        $this->assertIsArray($profile->getCodePages());
    }

    public function testQrcode()
    {
        $model = new Model('MP-4200 TH');
        $connection = new Buffer();
        $model->getProfile()->setConnection($connection);
        $profile = $model->getProfile();
        $profile->qrcode('https://github.com/mazinsw/escpos', 4);
        $this->assertEquals(
            PrinterTest::getExpectedBuffer('qrcode_bematech', $connection->getBuffer()),
            $connection->getBuffer()
        );
    }
}
