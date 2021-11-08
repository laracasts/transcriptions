<?php

namespace Tests;

use ArrayAccess;
use JsonSerializable;
use Laracasts\Transcriptions\Line;
use Laracasts\Transcriptions\Transcription;
use PHPUnit\Framework\TestCase;

class TranscriptionTest extends TestCase
{
    protected Transcription $transcription;

    protected function setUp(): void
    {
        $this->transcription = Transcription::load(
            __DIR__ . '/stubs/basic-example.vtt'
        );
    }

    /** @test */
    function it_loads_a_vtt_file_as_a_string()
    {
        $this->assertStringContainsString('Here is an', $this->transcription);
        $this->assertStringContainsString('example of a VTT file', $this->transcription);
    }

    /** @test */
    function it_can_convert_to_an_array_of_line_objects()
    {
        $lines = $this->transcription->lines();

        $this->assertCount(2, $lines);

        $this->assertContainsOnlyInstancesOf(Line::class, $lines);
    }

    /** @test */
    function it_works_with_multiline_strings()
    {
        $transcript = Transcription::load(
            __DIR__ . '/stubs/multi-line-example.vtt'
        );

        $this->assertCount(4, $transcript->lines());
        $this->assertEquals(
            'Here is an example of a VTT file with cards that include multiple lines before moving',
            $transcript->lines()[0]->body
        );
    }

    /** @test */
    function it_discards_irrelevant_lines_from_the_vtt_file()
    {
        $this->assertStringNotContainsString('WEBVTT', $this->transcription);
        $this->assertCount(2, $this->transcription->lines());
    }
}
