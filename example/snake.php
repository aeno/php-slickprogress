<?php

declare(strict_types=1);

use Aeno\SlickProgress\Colors;
use Aeno\SlickProgress\Progress;
use Aeno\SlickProgress\Theme\Snake;

require __DIR__.'/../vendor/autoload.php';

echo 'SNAKE --------------'.PHP_EOL;

$theme = new Snake();

$progress = new Progress($theme);
$progress->start(50);

for ($i=0;$i<50;$i++) {
    $progress->advance();
    usleep(50000);
}

$progress->finish();
echo PHP_EOL;

echo 'SNAKE WITH STATUS ---'.PHP_EOL;

$theme = new Snake();

$progress = new Progress($theme);
$progress->start(50);

for ($i=0;$i<50;$i++) {
    if ($i % 10 === 0) {
        $progress->setStatusMessage("Status Message - \$i === $i");
    }

    $progress->advance();
    usleep(50000);
}

$progress->finish();
echo PHP_EOL;

echo 'SNAKE WITH LABELS ---'.PHP_EOL;

$theme = new Snake();
$theme->showStep(true);
$theme->showPercent(true);

$progress = new Progress($theme);
$progress->start(50);

for ($i=0;$i<50;$i++) {
    $progress->advance();
    usleep(50000);
}

$progress->finish();

echo PHP_EOL;

echo 'SNAKE INDEFINITE ---'.PHP_EOL;

$theme = new Snake();

$progress = new Progress($theme);
$progress->start(-1);

for ($i=0;$i<100;$i++) {
    $progress->advance();
    usleep(50000);
}

$progress->finish();

echo PHP_EOL;

echo 'SNAKE INDEFINITE COLORED ---'.PHP_EOL;

$theme = new Snake();
$theme->setColorType(Colors::COLOR_TYPE_ANSI256);

$progress = new Progress($theme);
$progress->start(-1);

for ($i=0;$i<100;$i++) {
    $progress->advance();
    usleep(50000);
}

$progress->finish();
echo PHP_EOL;

echo 'SNAKE INDEFINITE COLORED WITH STATUS ---'.PHP_EOL;

$theme = new Snake();
$theme->setColorType(Colors::COLOR_TYPE_ANSI256);

$progress = new Progress($theme);
$progress->start(-1);

for ($i=0;$i<100;$i++) {
    if ($i % 10 === 0) {
        $progress->setStatusMessage("Status Message - \$i === $i");
    }

    $progress->advance();
    usleep(50000);
}

$progress->finish();
echo PHP_EOL;

echo 'SNAKE INDEFINITE WITH LABELS ---'.PHP_EOL;

$theme = new Snake();
$theme->showStep(true);
$theme->showPercent(true);

$progress = new Progress($theme);
$progress->start(-1);

for ($i=0;$i<100;$i++) {
    $progress->advance();
    usleep(50000);
}

$progress->finish();

echo PHP_EOL;

echo 'DONE!', PHP_EOL;

echo 'peak mem: '.memory_get_peak_usage(true), PHP_EOL;
