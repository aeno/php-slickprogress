<?php
/*
 * This file is part of aeno/php-slickprogress.
 * (c) Steffen Rieke <dev@aenogym.de>
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

/** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */

declare(strict_types=1);

namespace Aeno\SlickProgress\Theme;

use Aeno\SlickProgress\Colors;

class Snake extends AbstractTheme
{
    const PROGRESS_CHARS   = ['⠀', '⡀', '⡄', '⡆', '⡇', '⡏', '⡟', '⡿', '⣿'];
    const INDEFINITE_CHARS = ['⡇', '⠏', '⠛', '⠹', '⢸', '⣰', '⣤', '⣆'];

    /** @var bool */
    protected $_showPercent = false;

    /** @var bool */
    protected $_showStep = false;

    /** @var int */
    protected $_colorType = Colors::COLOR_TYPE_NONE;

    /** @var int[] */
    protected $colors = [];

    protected function render(): string
    {
        $result = $this->getSpinnerSymbol(
            $this->current,
            $this->max,
            $this->indefinite,
            $this->_colorType !== Colors::COLOR_TYPE_NONE
        );

        if ($this->_showStep) {
            if ($this->indefinite) {
                $result = " ? / ? {$result}";
            } else {
                $result = sprintf("% {$this->maxWidth}d / %d ", $this->current, $this->max) . $result;
            }
        }

        if ($this->_showPercent) {
            if ($this->indefinite) {
                $result .= '   ? %';
            } else {
                $percent = 100 / $this->max * $this->current;
                $result .= ' ' . sprintf("% 6.2f%%", $percent);
            }
        }

        if ($this->status !== null) {
            $result .= ' '.$this->status;

            if (extension_loaded('mbstring')) {
                $spaces = $this->terminalWidth - mb_strlen($result);
            } else {
                $spaces = $this->terminalWidth - strlen($result);
            }

            $result = $result . str_repeat(' ', max(0, $spaces)) . "\r";
        }

        return $result;
    }

    protected function getSpinnerSymbol(int $current, int $max, bool $indefinite, bool $colored): string
    {
        if ($indefinite) {
            $modulo = $current % count(self::INDEFINITE_CHARS);
            $result = self::INDEFINITE_CHARS[$modulo];
        } else {
            $percent = 100 / $max * $current;
            $position = (int) floor(count(self::PROGRESS_CHARS) / 100 * $percent);
            $position = max(0, min(count(self::PROGRESS_CHARS)-1, $position));

            $result = self::PROGRESS_CHARS[$position];
        }

        if ($colored) {
            $modulo = $current % count($this->colors);
            $color = $this->colors[$modulo];

            $result = "\033[38;5;{$color}m{$result}\033[0m";
        }

        return $result;
    }

    public function showPercent(bool $showPercent): self
    {
        $this->_showPercent = $showPercent;
        return $this;
    }

    public function showStep(bool $showStep): self
    {
        $this->_showStep = $showStep;
        return $this;
    }

    /**
     * @param int $colorType    One of the Colors::COLOR_TYPE_* constants
     * @return $this
     */
    public function setColorType(int $colorType): self
    {
        if (in_array($colorType, Colors::COLOR_TYPES)) {
            $this->_colorType = $colorType;
            $this->initColors();
        } else {
            $this->_colorType = Colors::COLOR_TYPE_NONE;
        }

        return $this;
    }

    /**
     *  Pre-compute ANSI colors representing a rainbow in 20° hue steps.
     */
    protected function initColors()
    {
        $this->colors = [];

        for ($hue = 0; $hue <= 360; $hue += 10) {
            $rgb = Colors::hsvToRgb($hue, 100, 100);

            if ($this->_colorType === Colors::COLOR_TYPE_ANSI16) {
                $this->colors[] = Colors::rgbToAnsi16(...$rgb);
            } else {
                $this->colors[] = Colors::rgbToAnsi256(...$rgb);
            }
        }
    }
}
