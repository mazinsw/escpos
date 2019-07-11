<?php
require(dirname(__DIR__) . '/vendor/autoload.php');

use Thermal\Printer;
use Thermal\Connection\Buffer;
use Thermal\Model;

$model = new Model('MP-4200 TH');

$connection = new Buffer();
$printer = new Printer($model, $connection);
$printer->qrcode('https://github.com/mazinsw/escpos');
$printer->buzzer();
$printer->cutter();
echo $connection->getBuffer();

// php examples/qrcode.php | lp -d MyCupsPrinterName
