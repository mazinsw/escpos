<?php

namespace Thermal\Profile;

use Thermal\Model;

class EscBemaTest extends \PHPUnit_Framework_TestCase
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
        $this->assertInternalType('array', $profile->getCodePages());
    }
}
