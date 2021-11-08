<?php

namespace Tests;

use ArrayAccess;
use JsonSerializable;
use Laracasts\Transcriptions\Line;
use Laracasts\Transcriptions\Lines;
use Laracasts\Transcriptions\Transcription;
use PHPUnit\Framework\TestCase;

class LinesTest extends TestCase
{
    protected Transcription $transcription;

    protected function setUp(): void
    {
        $this->transcription = Transcription::load(
            __DIR__ . '/stubs/basic-example.vtt'
        );
    }


    /** @test */
    function it_renders_the_lines_as_html()
    {
        $expected = <<<EOT
            <a href="?time=3" data-seconds="3">Here is an</a>
            <a href="?time=4" data-seconds="4">example of a VTT file.</a>
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

    /** @test */
    function it_can_render_as_json()
    {
        $lines = $this->transcription->lines();
        
        $this->assertInstanceOf(JsonSerializable::class, $lines);
        $this->assertJson(json_encode($lines));

        $this->assertInstanceOf(JsonSerializable::class, $lines[0]);

        $this->assertEquals([
            'body' => 'Here is an',
            'html' => $lines[0]->toHtml(),
            'timestamp' => '00:00:03.210 --> 00:00:04.110',
            'beginningTimestamp' => '00:03',
            'endingTimestamp' => '00:04',
            'beginningSeconds' => 3,
            'endingSeconds' => 4,
        ], $lines[0]->jsonSerialize());
    }

    /** @test */
    function it_groups_lines_by_sentence()
    {
        $lines = $this->transcription->lines()->groupBySentence();

        $this->assertCount(1, $lines);
        $this->assertEquals('Here is an example of a VTT file.', $lines[0]->body);
    }
}
