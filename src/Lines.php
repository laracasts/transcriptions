<?php

namespace Laracasts\Transcriptions;

class Lines extends Collection
{
    public function asHtml(): string
    {
        return $this->map(fn(Line $line) => $line->toHtml())
            ->__toString();
    }

    public function __toString(): string
    {
        return implode("\n", $this->items);
    }
}
