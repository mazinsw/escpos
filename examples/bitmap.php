<?php
require(dirname(__DIR__) . '/vendor/autoload.php');

use Thermal\Printer;
use Thermal\Connection\Buffer;
use Thermal\Model;
use Thermal\Graphics\Image;
use Thermal\Graphics\Filter\BayerOrdered;

$cache_name = dirname(__DIR__) . '/storage/sample.jpg.ser';
if (file_exists($cache_name)) {
    $image = unserialize(file_get_contents($cache_name));
} else {
    $image = new Image(__DIR__ . '/sample.jpg'/*, new BayerOrdered() */);
    file_put_contents($cache_name, serialize($image));
}
$model = new Model('TM-T20');
$connection = new Buffer();
$printer = new Printer($model, $connection);
$printer->draw($image, Printer::ALIGN_CENTER);
$printer->feed(6);
$printer->cutter();
echo $connection->getBuffer();
