[![PHP version](https://img.shields.io/badge/PHP-%3E%3D5.6-8892BF.svg?style=flat-square)](http://php.net)
[![Latest Version](https://img.shields.io/packagist/v/juliangut/body-parser.svg?style=flat-square)](https://packagist.org/packages/juliangut/body-parser)
[![License](https://img.shields.io/github/license/juliangut/body-parser.svg?style=flat-square)](https://github.com/juliangut/body-parser/blob/master/LICENSE)

[![Build Status](https://img.shields.io/travis/juliangut/body-parser.svg?style=flat-square)](https://travis-ci.org/juliangut/body-parser)
[![Style Check](https://styleci.io/repos/79265313/shield)](https://styleci.io/repos/79265313)
[![Code Quality](https://img.shields.io/scrutinizer/g/juliangut/body-parser.svg?style=flat-square)](https://scrutinizer-ci.com/g/juliangut/body-parser)
[![Code Coverage](https://img.shields.io/coveralls/juliangut/body-parser.svg?style=flat-square)](https://coveralls.io/github/juliangut/body-parser)

[![Total Downloads](https://img.shields.io/packagist/dt/juliangut/body-parser.svg?style=flat-square)](https://packagist.org/packages/juliangut/body-parser)
[![Monthly Downloads](https://img.shields.io/packagist/dm/juliangut/body-parser.svg?style=flat-square)](https://packagist.org/packages/juliangut/body-parser)

# body-parser

PSR7 request body parser middleware.

PSR7 implementations doesn't normally parse request body to be available through `$request->getParsedBody()` or they only do it for certain request methods or content types.

The best way to be fully confident that your request content will be parsed correctly while using the PSR7 implementation that you want is through the use of a middleware responsible of this task.

## Installation

### Composer

```
composer require juliangut/body-parser
```

## Usage

Add as many content decoders as you want to cover your application needs based on request's `Content-Type` header.

Decoders are assign to one or more HTTP methods.

Integrate in your middleware aware application workflow.

```php
require './vendor/autoload.php';

use Jgut\BodyParser\Decoder\Json;
use Jgut\BodyParser\Decoder\Urlencoded;
use Jgut\BodyParser\Parser;
use Negotiator\Negtiator;

$bodyParser = new Parser(new Negotiator());
$bodyParser->addDecoder(new Urlencoded()); // Assigned to all requests
$bodyParser->addDecoder(new Json(), ['POST', 'PUT']); // Assigned only to POST and PUT requests

$app = new \YourMiddlewareAwareApplication();
$app->addMiddleware($bodyParser);
$app->run();
```

*Review the documentation of the PSR7 implementation you use as it may already parse request body in some cases. You don't want to do the same job twice.*

### Decoders

#### URL encoded

```php
$decoder = new \Jgut\BodyParser\Decoder\UrlEncoded();
```

Supported MIME types:

* application/x-www-form-urlencoded

#### JSON

```php
$decoder = new \Jgut\BodyParser\Decoder\Json();
```

Supported MIME types:

* application/json
* text/json
* application/x-json

#### XML

```php
$decoder = new \Jgut\BodyParser\Decoder\Xml();
```

Supported MIME types:

* application/xml
* text/xml
* application/x-xml

#### CSV

```php
$decoder = new \Jgut\BodyParser\Decoder\Csv($delimiter = ',', $enclosure = '"', $escape = '\\');
```

Supported MIME types:

* text/csv

#### Custom

You can create your own decoder implementing `Jgut\BodyParser\Decoder\Decoder` interface.

For example you could implement a YAML decoder for `application/x-yaml` and `text/yaml` MIME types.

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/juliangut/body-parser/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/juliangut/body-parser/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/juliangut/body-parser/blob/master/LICENSE) included with the source code for a copy of the license terms.
