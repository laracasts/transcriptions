<?php

namespace Laracasts\Transcriptions;

use JsonSerializable;

class Line implements JsonSerializable
{
    public function __construct(
        public string $timestamp,
        public string $body
    )
    {
        //
    }

    public function toHtml(): string
    {
        return "<a href=\"?time={$this->beginningSeconds()}\" data-seconds=\"{$this->beginningSeconds()}\">{$this->body}</a>";
    }

    public function beginningTimestamp(): string
    {
        return $this->timestampSegment();
    }

    public function endingTimestamp(): string
    {
        return $this->timestampSegment(false);
    }

    public function beginningSeconds(): int
    {
        return $this->convertToSeconds($this->beginningTimestamp());
    }

    public function endingSeconds(): int
    {
        return $this->convertToSeconds($this->endingTimestamp());
    }

    protected function timestampSegment($beginning = true): string
    {
        preg_match_all('/\d{2}:(\d{2}:\d{2})\.\d{3}/', $this->timestamp, $matches);

        return $beginning ? $matches[1][0] : $matches[1][1];
    }

    protected function convertToSeconds(string $timestamp): int
    {
        $segments = explode(':', $timestamp);

        return intval($segments[0] * 60) + intval($segments[1]);
    }

    public static function isTimestamp($line): bool
    {
       return preg_match("/\d{2}:\d{2}:\d{2}\.\d{3}/", $line);
    }

    public function jsonSerialize(): array
    {
        return [
            'body' => $this->body,
            'timestamp' => $this->timestamp,
            'html' => $this->toHtml(),
            'beginningTimestamp' => $this->beginningTimestamp(),
            'endingTimestamp' => $this->endingTimestamp(),
            'beginningSeconds' => $this->beginningSeconds(),
            'endingSeconds' => $this->endingSeconds()
        ];
    }
}
