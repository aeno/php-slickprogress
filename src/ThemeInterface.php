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
    const FINISH_TYPE_INLINE = 'inline';
    const FINISH_TYPE_NEWLINE = 'newline';
    const FINISH_TYPE_CLEAR = 'clear';

    public function start(int $max = -1): string;
    public function setStatusMessage(?string $status): void;
    public function advance(int $step = 1): string;
    public function finish(string $type = self::FINISH_TYPE_NEWLINE): string;
    public function clear(): string;
}
