<?php
/*
 * This file is part of aeno/php-slickprogress.
 * (c) Steffen Rieke <dev@aenogym.de>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

declare(strict_types=1);

use Aeno\SlickProgress\Progress;
use Aeno\SlickProgress\Theme\Simple;

require __DIR__.'/../vendor/autoload.php';

echo 'SIMPLE --------------'.PHP_EOL;

$progress = new Progress();
$progress->start(50);

for ($i=0;$i<50;$i++) {
    $progress->advance();
    usleep(25000);
}

$progress->finish();
echo PHP_EOL;

echo 'SIMPLE WITH STATUS --------------'.PHP_EOL;

$theme = new Simple();

$progress = new Progress($theme);
$progress->start(200);

for ($i=0;$i<200;$i++) {
    if ($i % 25 === 0) {
        $progress->setStatusMessage("Status Message - \$i === $i");
    }

    $progress->advance();
    usleep(25000);
}

$progress->finish();
echo PHP_EOL;

echo 'SIMPLE INDEFINITE --------------'.PHP_EOL;

$progress = new Progress();
$progress->start(-1);

for ($i=0;$i<200;$i++) {
    $progress->advance();
    usleep(25000);
}

$progress->finish();

echo PHP_EOL;

echo 'SIMPLE WITH LABELS --------------'.PHP_EOL;

$theme = new Simple();
$theme->showStep(true);
$theme->showPercent(true);

$progress = new Progress($theme);
$progress->start(200);

for ($i=0;$i<200;$i++) {
    $progress->advance();
    usleep(25000);
}

$progress->finish();

echo PHP_EOL;

echo 'SIMPLE INDEFINITE WITH LABELS --------------'.PHP_EOL;

$theme = new Simple();
$theme->showStep(true);
$theme->showPercent(true);

$progress = new Progress($theme);
$progress->start(-1);

for ($i=0;$i<200;$i++) {
    $progress->advance();
    usleep(25000);
}

$progress->finish();

echo PHP_EOL;

echo 'DONE!'.PHP_EOL;
