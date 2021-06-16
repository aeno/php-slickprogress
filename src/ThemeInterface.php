<?php

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
