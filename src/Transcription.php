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
        return new Lines(array_map(
            fn($line) => new Line(...$line),
            array_chunk($this->lines, 2)
        ));
    }

    protected function normalizeLines(array $lines): array
    {
        $lines = array_slice(array_filter(array_map('trim', $lines)), 1);

        return array_filter($lines, fn($line) => !is_numeric($line));
    }

    public function __toString(): string
    {
        return implode("\n", $this->lines);
    }
}
