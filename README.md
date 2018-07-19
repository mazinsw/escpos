# ESC/POS Printer Library
## Library to generate buffer for thermal printers

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

## Install

You need [Composer][link-composer] to install this library.

Run command bellow on your project folder

```sh
composer require mazinsw/escpos
```

## Basic example
```php
<?php
use Thermal\Printer;
use Thermal\Connection\Buffer;
use Thermal\Model;

$model = new Model('MP-4200 TH');
$connection = new Buffer();
$printer = new Printer($model, $connection);
$printer->setColumns(56);
$printer->write('Simple Text *** ');
$printer->writeln('Bold Text', Printer::STYLE_BOLD);
$printer->writeln('Double height', Printer::STYLE_DOUBLE_HEIGHT | Printer::STYLE_BOLD, Printer::ALIGN_CENTER);
$printer->feed(2);
$printer->buzzer();
$printer->cutter();
$printer->drawer(Printer::DRAWER_1);
echo $connection->getBuffer();
// redirect the output to your printer
// php example.php > COM1
```

## Dependencies
- PHP 5.6 or above
- Mbstring extension

## License
Prease see [license file](/LICENSE.txt) for more information.

[ico-version]: https://poser.pugx.org/mazinsw/escpos/version
[ico-travis]: https://api.travis-ci.org/mazinsw/escpos.svg
[ico-scrutinizer]: https://scrutinizer-ci.com/g/mazinsw/escpos/badges/coverage.png
[ico-code-quality]: https://scrutinizer-ci.com/g/mazinsw/escpos/badges/quality-score.png
[ico-downloads]: https://poser.pugx.org/mazinsw/escpos/d/total.svg

[link-packagist]: https://packagist.org/packages/mazinsw/escpos
[link-travis]: https://travis-ci.org/mazinsw/escpos
[link-scrutinizer]: https://scrutinizer-ci.com/g/mazinsw/escpos/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/mazinsw/escpos
[link-downloads]: https://packagist.org/packages/mazinsw/escpos
[link-composer]: https://getcomposer.org
