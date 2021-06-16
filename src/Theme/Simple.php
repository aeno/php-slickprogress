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

class Simple extends AbstractTheme
{
    const SEGMENTS    = 30;
    const PREFIX      = '[';
    const SUFFIX      = ']';
    const FILLED_CHAR = '=';
    const BLANK_CHAR  = ' ';

    /** @var bool */
    protected $indefiniteMoveForward = false;

    /** @var bool */
    protected $_showPercent = false;

    /** @var bool */
    protected $_showStep = false;

    protected function render(): string
    {
        if ($this->indefinite) {
            $modulo = $this->current % self::SEGMENTS;

            $beforeSegments = min(self::SEGMENTS, $modulo);
            $afterSegments = self::SEGMENTS-$beforeSegments-1;

            // reverse moving direction
            if ($modulo === 0) {
                $this->indefiniteMoveForward = !$this->indefiniteMoveForward;
            }

            // if we're moving backward, swap before and after segment counts
            if (!$this->indefiniteMoveForward) {
                $tmp = $beforeSegments;
                $beforeSegments = $afterSegments;
                $afterSegments = $tmp;
            }

            $result =
                self::PREFIX
                . str_repeat(self::BLANK_CHAR, max(0, $beforeSegments))
                . self::FILLED_CHAR
                . str_repeat(self::BLANK_CHAR, max(0, $afterSegments))
                . self::SUFFIX;
        } else {
            $filledSegments = min(self::SEGMENTS, (int) ceil(self::SEGMENTS / $this->max * $this->current));
            $blankSegments = max(0, self::SEGMENTS - $filledSegments);

            $result =
                self::PREFIX
                . str_repeat(self::FILLED_CHAR, $filledSegments)
                . str_repeat(self::BLANK_CHAR, $blankSegments)
                . self::SUFFIX;
        }

        if ($this->_showStep) {
            if ($this->indefinite) {
                $result = ' ? / ? ' . $result;
            } else {
                $result = sprintf("% {$this->maxWidth}d / %d ", $this->current, $this->max) . $result;
            }
        }

        if ($this->_showPercent) {
            if ($this->indefinite) {
                $result .= '      ? %';
            } else {
                $percent = 100 / $this->max * $this->current;
                $result .= ' ' . sprintf("% 6.2f%%", $percent);
            }
        }

        if ($this->status !== null) {
            $result .= ' '.$this->status;
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
}
