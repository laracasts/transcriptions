# VTT Transcriptions

This small PHP package assists in the loading and parsing of VTT files.

## Usage

```php
use Laracasts\Transcriptions\Transcription;

$transcription = Transcription::load('path/to/file.vtt');

foreach ($transcription->lines() as $line) {
    // $line->body
    // $line->toHtml()
    
    // $line->timestamp->begin()
    // $line->timestamp->beginSeconds()
    // $line->timestamp->end()
    // $line->timestamp->endSeconds()
    
    // json_encode($line);
}

// Group lines into full sentences.
// $transcription->lines()->groupBySentence();
```

## License

This package is open-sourced software licensed under the [MIT](https://opensource.org/licenses/MIT) license.
