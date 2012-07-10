# How to Test

## Installation

Install [composer](https://github.com/composer/composer) to your ~/bin:

```sh
$ curl -s http://getcomposer.org/installer | php
```

Run composer and install depending packages:

```sh
$ composer.phar install
```

## Executing Tests

```sh
$ ./vendor/bin/phpunit
```

## View Reports


If you want to see code coverages, open Coverage/index.html.