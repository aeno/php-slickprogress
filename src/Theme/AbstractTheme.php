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

namespace Aeno\SlickProgress\Theme;

use Aeno\SlickProgress\ThemeInterface;

abstract class AbstractTheme implements ThemeInterface
{
    /** @var int Maximum progress value */
    protected $max = 100;

    /** @var null String width of maximum progress value number ("100" = 3) */
    protected $maxWidth = null;

    /** @var int Current progress value */
    protected $current = 0;

    /** @var bool Display progress as indefinite (if maximum is unknown) */
    protected $indefinite = false;

    /** @var string|null Current status message */
    protected $status = null;

    /**
     * Render the current progress output
     *
     * @return string
     */
    abstract protected function render(): string;

    public function start(int $max = -1): string
    {
        if ($max === -1) {
            $this->indefinite = true;
        } else {
            $this->setMax($max);
        }

        $this->current = 0;
        return $this->render();
    }

    public function setStatusMessage(?string $status): void
    {
        $this->status = $status;
    }

    public function advance(int $step = 1): string
    {
        $this->current += $step;
        return $this->resetCursor() . $this->render();
    }

    public function finish(string $type = self::FINISH_TYPE_NEWLINE): string
    {
        if ($type === self::FINISH_TYPE_CLEAR) {
            return $this->clear();
        }

        if ($this->indefinite) {
            $this->setMax($this->current);
            $this->indefinite = false;
        }

        $this->current = $this->max;
        $result = $this->resetCursor() . $this->render();

        switch ($type) {
            case self::FINISH_TYPE_NEWLINE:
                return $result . PHP_EOL;

            case self::FINISH_TYPE_INLINE:
            default:
                return $result;
        }
    }

    public function clear(): string
    {
        // clear entire line
        return "\r\033[2K";
    }

    protected function resetCursor(): string
    {
        return "\r";
    }

    protected function setMax(int $max): void
    {
        $this->max = $max;
        $this->maxWidth = strlen((string) $this->max);
    }
}
