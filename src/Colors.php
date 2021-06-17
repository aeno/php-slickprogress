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

/**
 * Static helper methods for color conversions.
 *
 * @package Aeno\SlickProgress
 */
class Colors
{
    const COLOR_TYPE_NONE = 0;
    const COLOR_TYPE_ANSI16 = 1;
    const COLOR_TYPE_ANSI256 = 2;

    const COLOR_TYPES = [
        self::COLOR_TYPE_NONE,
        self::COLOR_TYPE_ANSI16,
        self::COLOR_TYPE_ANSI256,
    ];

    /**
     * Calculate a terminal ANSI escape code color (16 colors) from RGB.
     *
     * @param int $r
     * @param int $g
     * @param int $b
     * @return int Ansi 16 color code
     *
     * This function is ported from the color-convert JS library at
     * {@link https://github.com/Qix-/color-convert}, (c) Heather Arthur, Licensed
     * under the MIT License.
     * @author Heather Arthur <fayearthur@gmail.com>
     * @author Josh Junon <junon.me>
     * @copyright Heather Arthur <fayearthur@gmail.com>
     * @license MIT
     * @see https://github.com/Qix-/color-convert/blob/427cbb70540bb9e5b3e94aa3bb9f97957ee5fbc0/conversions.js#L525-L547
     */
    public static function rgbToAnsi16(int $r, int $g, int $b): int
    {
        // hsv -> ansi16 optimization
        if ($r === 1 || $g === 1 || $b === 1) {
            $value = $g;
        } else {
            $value = self::rgbToHsv($r, $g, $b)[2];
        }

        $value = (int) round($value / 50);

        if ($value === 0) {
            return 30;
        }

        $ansi = 30 + (
            (round($b / 255) << 2)
            | (round($g / 255) << 1)
            | (round($r / 255))
        );

        if ($value === 2) {
            $ansi += 60;
        }

        return $ansi;
    }

    /**
     * Calculate a terminal ANSI escape code color (256 colors) from RGB.
     *
     * @param int $r
     * @param int $g
     * @param int $b
     * @return int Ansi 256 color code
     *
     * This function is ported from the color-convert JS library at
     * {@link https://github.com/Qix-/color-convert}, (c) Heather Arthur, Licensed
     * under the MIT License.
     * @author Heather Arthur <fayearthur@gmail.com>
     * @author Josh Junon <junon.me>
     * @copyright Heather Arthur <fayearthur@gmail.com>
     * @license MIT
     * @see https://github.com/Qix-/color-convert/blob/427cbb70540bb9e5b3e94aa3bb9f97957ee5fbc0/conversions.js#L555-L580
     */
    public static function rgbToAnsi256(int $r, int $g, int $b): int
    {
        // we use the extended greyscale palette here, with the exception of
        // black and white. normal palette only has 4 greyscale shades.
        if ($r === $g && $g === $b) {
            if ($r < 8) {
                return 16;
            }

            if ($r > 248) {
                return 231;
            }

            return round((($r - 8) / 247) * 24) + 232;
        }

        return (int) (16
            + (36 * round($r / 255 * 5))
            + (6 * round($g / 255 * 5))
            + (round($b / 255 * 5)));
    }

    /**
     * Calculate a HSV from RGB.
     *
     * @param int $r
     * @param int $g
     * @param int $b
     * @return int[] HSV color components
     *
     * This function is ported from the color-convert JS library at
     * {@link https://github.com/Qix-/color-convert}, (c) Heather Arthur, Licensed
     * under the MIT License.
     * @author Heather Arthur <fayearthur@gmail.com>
     * @author Josh Junon <junon.me>
     * @copyright Heather Arthur <fayearthur@gmail.com>
     * @license MIT
     * @see https://github.com/Qix-/color-convert/blob/427cbb70540bb9e5b3e94aa3bb9f97957ee5fbc0/conversions.js#L85-L121
     */
    public static function rgbToHsv(int $r, int $g, int $b): array
    {
        $min = min($r, $g, $b);
        $max = max($r, $g, $b);
        $delta = $max - $min;

        if ($max === 0) {
            $s = 0;
        } else {
            $s = ($delta / $max * 1000) / 10;
        }

        if ($max === $min) {
            $h = 0;
        } else if ($r === $max) {
            $h = ($g - $b) / $delta;
        } else if ($g === $max) {
            $h = 2 + ($b - $r) / $delta;
        } else if ($b === $max) {
            $h = 4 + ($r - $g) / $delta;
        }

        /** @noinspection PhpUndefinedVariableInspection */
        $h = min($h * 60, 360);

        if ($h < 0) {
            $h += 360;
        }

        $v = (($max / 255) * 1000) / 10;

        return [(int)$h, (int)$s, (int)$v];
    }

    /**
     * Calculate an RGB from HSV.
     *
     * @param int $h 0-360
     * @param int $s 0-100
     * @param int $v 0-100
     * @return int[] RGB color components
     *
     * This function is ported from the color-convert JS library at
     * {@link https://github.com/Qix-/color-convert}, (c) Heather Arthur, Licensed
     * under the MIT License.
     * @author Heather Arthur <fayearthur@gmail.com>
     * @author Josh Junon <junon.me>
     * @copyright Heather Arthur <fayearthur@gmail.com>
     * @license MIT
     * @see https://github.com/Qix-/color-convert/blob/427cbb70540bb9e5b3e94aa3bb9f97957ee5fbc0/conversions.js#L302-L328
     */
    public static function hsvToRgb(int $h, int $s, int $v): array
    {
        $h = $h / 60;
        $s = $s / 100;
        $v = $v / 100;
        $hi = floor($h) % 6;

        $f = $h - floor($h);
        $p = 255 * $v * (1 - $s);
        $q = 255 * $v * (1 - ($s * $f));
        $t = 255 * $v * (1 - ($s * (1 - $f)));
        $v *= 255;

        $p = (int)$p;
        $q = (int)$q;
        $t = (int)$t;
        $v = (int)$v;

        switch ($hi) {
            case 0:
                return [$v, $t, $p];
            case 1:
                return [$q, $v, $p];
            case 2:
                return [$p, $v, $t];
            case 3:
                return [$p, $q, $v];
            case 4:
                return [$t, $p, $v];
            case 5:
                return [$v, $p, $q];
            default:
                // because $hi will never be out of 0..5, the default will
                // never be reached. but it comforts code checkers.
                return [];
        }
    }
}
