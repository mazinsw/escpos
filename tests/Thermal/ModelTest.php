<?php

namespace Thermal;

use Thermal\Profile\Epson;

class ModelTest extends \PHPUnit\Framework\TestCase
{
    public function testGetAll()
    {
        $expected = [
            'MP-5100 TH' => ['brand' => 'Bematech'],
            'MP-4200 TH' => ['brand' => 'Bematech'],
            'MP-20 MI' => ['brand' => 'Bematech'],
            'MP-100S TH' => ['brand' => 'Bematech'],
            'TM-T20' => ['brand' => 'Epson'],
            'TM-T81' => ['brand' => 'Epson'],
            'TM-T88' => ['brand' => 'Epson'],
            'NIX' => ['brand' => 'Elgin'],
            'VOX+' => ['brand' => 'Elgin'],
            'VOX' => ['brand' => 'Elgin'],
            'I9' => ['brand' => 'Elgin'],
            'I7' => ['brand' => 'Elgin'],
            'DS300' => ['brand' => 'Daruma'],
            'DS348' => ['brand' => 'Daruma'],
            'DR600' => ['brand' => 'Daruma'],
            'DR700' => ['brand' => 'Daruma'],
            'DR800' => ['brand' => 'Daruma'],
            'IM113' => ['brand' => 'Diebold'],
            'IM402' => ['brand' => 'Diebold'],
            'IM433' => ['brand' => 'Diebold'],
            'IM453' => ['brand' => 'Diebold'],
            'TSP-143' => ['brand' => 'Diebold'],
            'SI-250' => ['brand' => 'Sweda'],
            'SI-300L' => ['brand' => 'Sweda'],
            'SI-300S' => ['brand' => 'Sweda'],
            'SI-300W' => ['brand' => 'Sweda'],
            'E-3202' => ['brand' => 'Dataregis'],
            'DT200' => ['brand' => 'Dataregis'],
            'IM833' => ['brand' => 'Diebold'],
            'PrintiD' => ['brand' => 'ControliD'],
            'PertoPrinter' => ['brand' => 'Perto'],
            'CMP-20' => ['brand' => 'Citizen'],
        ];
        $list = Model::getAll();
        $list = array_map(function ($value) {
            return [ 'brand' => $value['brand'] ];
        }, $list);
        $this->assertEquals($expected, $list);
    }

    public function testGetName()
    {
        $model = new Model('MP-4200 TH');
        $this->assertEquals('Bematech MP-4200 TH', $model->getName());
    }

    public function testModelNotFound()
    {
        $this->expectException('\Exception');
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
