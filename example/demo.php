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

use Aeno\SlickProgress\Colors;
use Aeno\SlickProgress\Progress;
use Aeno\SlickProgress\Theme\Simple;
use Aeno\SlickProgress\Theme\Snake;
use Aeno\SlickProgress\ThemeInterface;

require __DIR__.'/../vendor/autoload.php';

if ($argc === 1) {
    echo "Please provide a demo style as argument.", PHP_EOL;
    die();
}

$demo = $argv[1];

$holdOnTexts = [
    'Hold on...',
    'Just a second...',
    'Still working...',
    'The cat ran away! Need to catch her quickly...',
    "I'm still here!",
    'Oh wow, just look at the time!',
];

switch ($demo) {
    case "simple":
        $progress = new Progress();
        $progress->start(50);

        for ($i=0;$i<50;$i++) {
            $progress->advance();
            usleep(25000);
        }

        $progress->finish();
        echo PHP_EOL;
        break;

    case "indefinite":
        $progress = new Progress();
        $progress->start(-1);

        for ($i=0;$i<200;$i++) {
            $progress->advance();
            usleep(25000);
        }

        $progress->finish();
        echo PHP_EOL;
        break;

    case "detailed":
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
        break;

    case "spinner":
        $theme = new Snake();
        $theme->setColorType(Colors::COLOR_TYPE_ANSI256);

        $progress = new Progress($theme);
        $progress->start(-1);

        for ($i=0;$i<100;$i++) {
            if ($i % 20 === 0) {
                $progress->setStatusMessage($holdOnTexts[array_rand($holdOnTexts)]);
            }

            $progress->advance();
            usleep(50000);
        }

        $progress->finish(ThemeInterface::FINISH_TYPE_MESSAGE, '✅ Done!');
        echo PHP_EOL;
        break;
}

