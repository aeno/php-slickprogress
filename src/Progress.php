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

    /**
     * Create a new progress with the specified theme.
     *
     * @param ThemeInterface|null $theme    The theme to use. Defaults to {@link Simple}
     */
    public function __construct(ThemeInterface $theme = null)
    {
        $this->theme = $theme === null
            ? new Simple()
            : $theme;
    }

    /**
     * Initialize and start the progress.
     *
     * @param int $max  Maximum progress value or -1, if maximum is unknown.
     */
    public function start(int $max = -1): void
    {
        echo $this->theme->start($max);
    }

    /**
     * Set a status message to be displayed along the progress.
     * Set null to clear the message.
     *
     * @param string|null $status
     */
    public function setStatusMessage(?string $status): void
    {
        $this->theme->setStatusMessage($status);
    }

    /**
     * Advance the progress.
     *
     * @param int $step Progress steps to advance.
     */
    public function advance(int $step = 1): void
    {
        echo $this->theme->advance($step);
    }

    /**
     * Finish the progress and render a clean state.
     * Always call this when your task has finished!
     *
     * @param string      $type     One of the {@link ThemeInterface FINISH_TYPE_*} constants.
     * @param string|null $message  Optional message, if {@link ThemeInterface::FINISH_TYPE_MESSAGE} is used.
     */
    public function finish(string $type = ThemeInterface::FINISH_TYPE_NEWLINE, ?string $message = null): void
    {
        echo $this->theme->finish($type, $message);
    }

    /**
     * Clear the entire line.
     */
    public function clear(): void
    {
        echo $this->theme->clear();
    }
}
