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

namespace Aeno\SlickProgress;

use Aeno\SlickProgress\Theme\Simple;

class Progress
{
    /**
     * @var ThemeInterface
     */
    protected $theme;

    public function __construct(ThemeInterface $theme = null)
    {
        $this->theme = $theme === null
            ? new Simple()
            : $theme;
    }

    public function start(int $max = -1): void
    {
        echo $this->theme->start($max);
    }

    public function setStatusMessage(?string $status): void
    {
        $this->theme->setStatusMessage($status);
    }

    public function advance(int $step = 1): void
    {
        echo $this->theme->advance($step);
    }

    public function finish(string $type = ThemeInterface::FINISH_TYPE_NEWLINE): void
    {
        echo $this->theme->finish($type);
    }

    public function clear(): void
    {
        echo $this->theme->clear();
    }
}
