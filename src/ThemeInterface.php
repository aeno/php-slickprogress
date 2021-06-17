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

interface ThemeInterface
{
    /**#@+
     * Progress finish types
     * @var string
     */
    /** Finish the progress inline - do not add a line break. */
    const FINISH_TYPE_INLINE  = 'inline';
    /** Finish the progress with a line break. */
    const FINISH_TYPE_NEWLINE = 'newline';
    /** Clear the progress output line, effectively hiding the progress. */
    const FINISH_TYPE_CLEAR   = 'clear';
    /** Replace the progress output line with a message and add a line break. */
    const FINISH_TYPE_MESSAGE = 'message';
    /**#@-*/

    /**
     * Initialize and start the progress output.
     *
     * @param int $max  Maximum progress value or -1, if maximum is unknown.
     * @return string   Output of initially rendered progress frame
     */
    public function start(int $max = -1): string;

    /**
     * Set a status message to be displayed along the progress.
     * Set null to clear the message.
     *
     * @param string|null $status
     */
    public function setStatusMessage(?string $status): void;

    /**
     * Advance the progress.
     *
     * @param int $step Progress steps to advance.
     * @return string   Output of rendered progress frame.
     */
    public function advance(int $step = 1): string;

    /**
     * Finish the progress and render a clean state.
     * Always call this when your task has finished!
     *
     * @param string      $type     One of the {@link ThemeInterface FINISH_TYPE_*} constants.
     * @param string|null $message  Optional message, if {@link ThemeInterface::FINISH_TYPE_MESSAGE} is used.
     * @return string               Output of the final rendered progress frame.
     */
    public function finish(string $type = self::FINISH_TYPE_NEWLINE, ?string $message = null): string;

    /**
     * Clear the entire line.
     *
     * @return string
     */
    public function clear(): string;
}
