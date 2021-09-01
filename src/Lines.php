<?php

namespace Laracasts\Transcriptions;

class Lines extends Collection
{
    public function groupBySentence(): self
    {
        $lines = [];

        foreach ($this->items as $index => $line) {
            if ($index > 0 && ! preg_match('/[.?!]$/', $lines[$index - 1]->body)) {
                $lines[$index - 1]->body .= " {$line->body}";
            } else {
                $lines[] = $line;
            }
        }

        return new static($lines);
    }

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
