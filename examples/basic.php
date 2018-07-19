<?php
require(dirname(__DIR__) . '/vendor/autoload.php');

use Thermal\Printer;
use Thermal\Connection\Buffer;
use Thermal\Model;

$model = new Model('MP-4200 TH');
$connection = new Buffer();
$printer = new Printer($model, $connection);
$printer->setColumns(56);
$printer->write('Simple Text *** ');
$printer->writeln('Bold Text -> complete line text.[]123456', Printer::STYLE_BOLD);
$printer->writeln('Double height', Printer::STYLE_DOUBLE_HEIGHT | Printer::STYLE_BOLD, Printer::ALIGN_CENTER);
$printer->writeln('Áçênts R$ 5,00', Printer::STYLE_DOUBLE_HEIGHT | Printer::STYLE_DOUBLE_WIDTH);
$printer->feed(6);
$printer->buzzer();
$printer->cutter();
$printer->drawer(Printer::DRAWER_1);
echo $connection->getBuffer();
