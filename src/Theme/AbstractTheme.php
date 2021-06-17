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

    /** @var int Current terminal width */
    protected $terminalWidth;

    public function __construct()
    {
        // read current terminal width (columns)
        $cols = exec('tput cols');
        $this->terminalWidth = !$cols
            ? 80
            : (int) $cols;
    }

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
        $hideCursor = $this->hideCursor();

        return $hideCursor . $this->render();
    }

    public function setStatusMessage(?string $status): void
    {
        if ($status === null) {
            $this->status = null;
            return;
        }

        // use mbstring functions, if possible
        if (extension_loaded('mbstring')) {
            $substr = 'mb_substr';
            $strpos = 'mb_strpos';
        } else {
            $substr = 'substr';
            $strpos = 'strpos';
        }

        // cap message at terminal width - 10
        $status        = call_user_func($substr, $status, 0, $this->terminalWidth - 10);

        // only allow one line
        $breakPosition = call_user_func($strpos, $status, "\n");
        if ($breakPosition !== false) {
            $status    = call_user_func($substr, $status, 0, $breakPosition);
        }

        $this->status = trim($status);
    }

    public function advance(int $step = 1): string
    {
        $this->current += $step;
        return $this->resetCursor() . $this->render();
    }

    public function finish(string $type = self::FINISH_TYPE_NEWLINE, ?string $message = null): string
    {
        $showCursor = $this->showCursor();

        if ($type === self::FINISH_TYPE_CLEAR) {
            return $this->clear() . $showCursor;
        }

        if ($this->indefinite) {
            $this->setMax($this->current);
            $this->indefinite = false;
        }

        $this->current = $this->max;

        switch ($type) {
            case self::FINISH_TYPE_NEWLINE:
                return $this->resetCursor() . $this->render() . PHP_EOL . $showCursor;

            case self::FINISH_TYPE_MESSAGE:
                return $this->clear() . $message . PHP_EOL . $showCursor;

            case self::FINISH_TYPE_INLINE:
            default:
                return $this->resetCursor() . $this->render() . $showCursor;
        }
    }

    public function clear(): string
    {
        // clear entire line
        return "\r\033[2K";
    }

    protected function renderStatus(string $output): string
    {
        if ($this->status !== null) {
            $output .= ' '.$this->status;

            if (extension_loaded('mbstring')) {
                $spaces = $this->terminalWidth - mb_strlen($output);
            } else {
                $spaces = $this->terminalWidth - strlen($output);
            }

            $output = $output . str_repeat(' ', max(0, $spaces)) . "\r";
        }

        return $output;
    }

    protected function resetCursor(): string
    {
        return "\r";
    }

    protected function setMax(int $max): void
    {
        $this->max = $max;
        /** @psalm-suppress InvalidPropertyAssignmentValue */
        $this->maxWidth = strlen((string) $this->max);
    }

    protected function showCursor(): string
    {
        return "\033[?25h";
    }

    protected function hideCursor(): string
    {
        return "\033[?25l";
    }
}
