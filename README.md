# VTT Transcriptions

This small PHP package assists in the loading and parsing of VTT files.

## Usage

```php
use Laracasts\Transcriptions\Transcription;

$transcription = Transcription::load('path/to/file.vtt');

foreach ($transcription->lines() as $line) {
    // $line->timestamp
    // $line->body
    // $line->toHtml()
}
```

## Tests

```bash
composer test
```


## License

This package is open-sourced software licensed under the [MIT](https://opensource.org/licenses/MIT) license.
