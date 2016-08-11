# GOCR for PHP

A wrapper to work with Gocr inside PHP scripts.

## Why?

This project makes easier the use of the OCR software named GOCR.

## Dependencies

-  [gocr](http://jocr.sourceforge.net/)

## Installation

```bash
$ composer require shinbuntu/gocr-php
```

## Usage

#### Instantiate the Gocr object with the image path:
```php
$gocr = new Gocr('testData/images/welcome.png');
```

#### Recognize and get text content:
```php
$textContent = $gocr->recognize();
```

#### Set spacewidth between words in units of dots (default: 0 for autodetect):
Wider widths are interpreted as word spaces, smaller as character spaces.
```php
$gocr->setSpaceWidthParam(20);
```

#### Set Operational mode; mode is a bitfield (default: 0):
```php
$gocr->setModeParam(258);
```

#### Set value for certainty of recognition (0..100; default: 95):
Characters with a higher certainty are accepted, characters with a lower certainty are treated as unknown (not recognized); set higher values, if you want to have only more certain recognized characters.
```php
$gocr->setValueForCertaintyOfRecognitionParam(100);
```

#### Set database path, a final slash must be included, default is ./db/:
This path will be populated with images of learned characters.
```php
$gocr->setDatabasePathParam('testData/db/');
```

## Contributing

See the [CONTRIBUTING](CONTRIBUTING.md) file.

## License

The project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
