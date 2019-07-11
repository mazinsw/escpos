<?php

namespace Thermal;

use Thermal\Profile\Epson;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAll()
    {
        $list = Model::getAll();
        $this->assertInternalType('array', $list);
    }

    public function testGetName()
    {
        $model = new Model('MP-4200 TH');
        $this->assertEquals('Bematech MP-4200 TH', $model->getName());
    }

    public function testModelNotFound()
    {
        $this->setExpectedException('\Exception');
        new Model('Unknow Model');
    }

    public function testReuseProfile()
    {
        $profile = new Epson([
            'profile' => 'epson',
            'model' => 'Custom',
            'brand' => 'Unknow',
            'codepage' => 'UTF-8',
            'columns' => 32,
            'fonts' => [
                [
                    'name' => 'Font A',
                    'columns' => 32
                ]
            ]
        ]);
        $model = new Model($profile);
        $this->assertEquals('UTF-8', $model->getProfile()->getDefaultCodePage());
        $this->assertEquals('Unknow Custom', $model->getName());
    }

    public function testExtendCapabilities()
    {
        $model = new Model([
            'profile' => 'epson',
            'model' => 'Custom',
            'brand' => 'Unknow',
            'codepage' => 'UTF-8',
            'columns' => 32,
            'fonts' => [
                [
                    'name' => 'Font A',
                    'columns' => 32
                ]
            ]
        ]);
        $this->assertEquals('UTF-8', $model->getProfile()->getDefaultCodePage());
        $this->assertEquals('Unknow Custom', $model->getName());
    }
}
