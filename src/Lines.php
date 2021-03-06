<?php

namespace Laracasts\Transcriptions;

class Lines extends Collection
{
    public function groupBySentence(): self
    {
        return new static(array_reduce($this->items, function ($lines, $line) {
            if (! empty($lines) && !preg_match('/[.?!]$/', end($lines)->body)) {
                end($lines)->body .= " {$line->body}";
            } else {
                $lines[] = $line;
            }

            return $lines;
        }, []));
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
