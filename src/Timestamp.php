<?php

namespace Laracasts\Transcriptions;

class Timestamp
{
    public function __construct(protected string $timestamp)
    {
        //
    }

    public function begin(): string
    {
        return $this->match()[1][0];
    }
    
    public function end(): string
    {
        return $this->match()[1][1];
    }
    
    public function beginSeconds(): int
    {
        return $this->convertToSeconds($this->begin());
    }

    public function endSeconds(): int
    {
        return $this->convertToSeconds($this->end());
    }

    public static function validate($line): bool
    {
        return preg_match('/\d{2}:(\d{2}:\d{2})\.\d{3}/', $line);
    }

    protected function convertToSeconds(string $timestamp): int
    {
        $segments = explode(':', $timestamp);

        return intval($segments[0] * 60) + intval($segments[1]);
    }

    protected function match(): array
    {
        preg_match_all('/\d{2}:(\d{2}:\d{2})\.\d{3}/', $this->timestamp, $matches);

        return $matches;
    }

    public function __toString()
    {
        return $this->timestamp;
    }
}