# How to Test

[![Build Status](https://secure.travis-ci.org/suin/xoopsunit.png?branch=master)](http://travis-ci.org/suin/xoopsunit)

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