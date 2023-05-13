# HTTP reason phrase lookup

This library is a PHP 7.1 library dedicated to lookup HTTP reason phrases. It is using the [reason phrases list](https://github.com/guzzle/psr7/blob/master/src/Response.php#L15) from the [Guzzle PSR-7 package](https://packagist.org/packages/guzzlehttp/psr7).

## Usage

```php
<?php
use CodeInc\HttpReasonPhraseLookup\HttpReasonPhraseLookup;

// you can lookup a given status code 
HttpReasonPhraseLookup::getReasonPhrase(404); // returns 'Not Found'
HttpReasonPhraseLookup::getReasonPhrase(999); // returns null

// or list all the reason phrases
foreach (HttpReasonPhraseLookup::getReasonPhrases() as $statusCode => $reasonPhrase) {
    echo "$statusCode => $reasonPhrase\n";
}
```

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/http-reason-phrase-lookup) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/http-reason-phrase-lookup
```

## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).


