# HTTP Reason Phrase Lookup

![CI](https://github.com/CodeIncHQ/http-reason-phrase-lookup/actions/workflows/ci.yml/badge.svg)
[![Packagist Version](https://img.shields.io/packagist/v/codeinc/http-reason-phrase-lookup)](https://packagist.org/packages/codeinc/http-reason-phrase-lookup)
[![Packagist Downloads](https://img.shields.io/packagist/dt/codeinc/http-reason-phrase-lookup)](https://packagist.org/packages/codeinc/http-reason-phrase-lookup)
[![Packagist License](https://img.shields.io/packagist/l/codeinc/http-reason-phrase-lookup)](LICENSE)

A PHP 8.2+ library for looking up HTTP status code reason phrases.\
Covers all [IANA-registered HTTP status codes](https://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml) (1xx through 5xx).

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/http-reason-phrase-lookup) and can be installed using [Composer](https://getcomposer.org/):

```bash
composer require codeinc/http-reason-phrase-lookup
```

## Usage

```php
use CodeInc\HttpReasonPhraseLookup\HttpReasonPhraseLookup;

// Look up a reason phrase by status code
HttpReasonPhraseLookup::getReasonPhrase(200); // 'OK'
HttpReasonPhraseLookup::getReasonPhrase(404); // 'Not Found'
HttpReasonPhraseLookup::getReasonPhrase(999); // null

// Check whether a status code is known
HttpReasonPhraseLookup::hasReasonPhrase(200); // true
HttpReasonPhraseLookup::hasReasonPhrase(999); // false

// List all known status codes and reason phrases
foreach (HttpReasonPhraseLookup::getAllReasonPhrases() as $statusCode => $reasonPhrase) {
    echo "$statusCode => $reasonPhrase\n";
}
```

## License

This library is published under the MIT license (see the [LICENSE](LICENSE) file).
