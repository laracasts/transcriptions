<?php

namespace Laracasts\Transcriptions;

use Illuminate\Support\Facades\Storage;

class Transcription
{
    public function __construct(protected array $lines)
    {
        $this->lines = $this->normalizeLines($lines);
    }

    public static function load(string $transcriptOrPath): self
    {
        $transcript = null;

        if (preg_match('/WEBVTT/', $transcriptOrPath)) {
            $transcript = explode("\n", $transcriptOrPath);
        } else {
            $transcript = file($transcriptOrPath);
        }

        return new static($transcript);
    }

    public function lines(): Lines
    {
        return new Lines(array_map(fn($line) => new Line(...$line), array_chunk($this->lines, 2)));
    }

    protected function normalizeLines(array $lines): array
    {
        // 1. We'll start by trimming everything before the first timestamp.
        foreach ($lines as $index => $line) {
            if (TimestampSpan::validate($line)) {
                $lines = array_slice($lines, $index);

                break;
            }
        }

        // 2. Then, we'll remove all empty lines or numeric headlines.
        $lines = array_filter(
            array_map('trim', $lines),
            fn($line) => $line && !preg_match('/^\s*\d+$/', $line)
        );

        // 3. Finally, we'll allow for multi-line strings.
        $results = [];
        $lastMatch = 0;

        foreach ($lines as $index => $line) {
            if (TimestampSpan::validate($line)) {
                $lastMatch = $index;
                $results[$lastMatch] = $line;
            } else {
                $results[$lastMatch + 1] ??= '';
                $results[$lastMatch + 1] .= " {$line}";
            }
        }

        return array_values(array_map('trim', $results));
    }

    public function __toString(): string
    {
        return implode("\n", $this->lines);
    }
}
