<?php

namespace Laracasts\Transcriptions;

use JsonSerializable;

class Line implements JsonSerializable
{
    public TimestampSpan $timestamp;

    public string $body;

    public function __construct(string $timestamp, string $body = '')
    {
        $this->timestamp = new TimestampSpan($timestamp);
        $this->body = $body;
    }

    public function toHtml(): string
    {
        $seconds = $this->timestamp->beginSeconds();

        return "<a href=\"?time={$seconds}\" data-seconds=\"{$seconds}\">{$this->body}</a>";
    }

    public function jsonSerialize(): array
    {
        return [
            "body" => $this->body,
            "timestamp" => $this->timestamp,
            "html" => $this->toHtml(),
            "beginningTimestamp" => $this->timestamp->begin(),
            "endingTimestamp" => $this->timestamp->end(),
            "beginningSeconds" => $this->timestamp->beginSeconds(),
            "endingSeconds" => $this->timestamp->endSeconds(),
        ];
    }
}
