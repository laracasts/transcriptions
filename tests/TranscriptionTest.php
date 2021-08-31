<?php

namespace Tests;

use ArrayAccess;
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
        $this->assertStringContainsString('Here is a', $this->transcription);
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
    function it_discards_irrelevant_lines_from_the_vtt_file()
    {
        $this->assertStringNotContainsString('WEBVTT', $this->transcription);
        $this->assertCount(2, $this->transcription->lines());
    }

    /** @test */
    function it_renders_the_lines_as_html()
    {
        $expected = <<<EOT
            <a href="?time=00:03">Here is a</a>
            <a href="?time=00:04">example of a VTT file.</a>
            EOT;

        $this->assertEquals($expected, $this->transcription->lines()->asHtml());
    }

    /** @test */
    function it_supports_array_access()
    {
        $lines = $this->transcription->lines();

        $this->assertInstanceOf(ArrayAccess::class, $lines);
        $this->assertInstanceOf(Line::class, $lines[0]);
    }
}
