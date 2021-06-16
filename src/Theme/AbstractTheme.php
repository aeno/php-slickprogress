<?php

declare(strict_types=1);

namespace Aeno\SlickProgress\Theme;

use Aeno\SlickProgress\ThemeInterface;

abstract class AbstractTheme implements ThemeInterface
{
    protected $max = 100;
    protected $maxWidth = null;     // string width of maximum value
    protected $current = 0;

    /** @var bool */
    protected $indefinite = false;

    /** @var string|null */
    protected $status = null;

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
