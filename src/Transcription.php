<?php

namespace Laracasts\Transcriptions;

class Transcription
{
    public function __construct(protected array $lines)
    {
        $this->lines = $this->normalizeLines($lines);
    }

    public static function load(string $path): self
    {
        return new static(file($path));
    }

    public function lines(): Lines
    {
        return new Lines(
            array_map(
                fn($line) => new Line(...$line),
                array_chunk($this->lines, 2)
            )
        );
    }

    protected function normalizeLines(array $lines): array
    {
        // 1. We'll start by trimming everything before the first timestamp.
        foreach ($lines as $index => $line) {
            if (Line::isTimestamp($line)) {
                $lines = array_slice($lines, $index);

                break;
            }
        }

        // 2. Then, we'll remove all empty lines or numeric headlines.
        $lines = array_filter(
            array_map("trim", $lines),
            fn($line) => $line && !is_numeric($line)
        );

        // 3. Last, we'll allow for multi-line strings.
        $index = 0;
        $lines = array_reduce(
            $lines,
            function ($carry, $line) use (&$index) {
                if (Line::isTimestamp($line)) {
                    $index = count($carry) - 1;

                    $carry[$index] = $line;
                } else {
                    $carry[$index + 1] ??= "";
                    $carry[$index + 1] .= " " . $line;
                }

                return $carry;
            },
            []
        );

        return array_map("trim", $lines);
    }

    public function __toString(): string
    {
        return implode("\n", $this->lines);
    }
}
