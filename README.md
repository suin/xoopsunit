# XoopsUnit: Simplify Your Test Code

* master : [![Build Status](https://secure.travis-ci.org/suin/xoopsunit.png?branch=master)](http://travis-ci.org/suin/xoopsunit)
* develop : [![Build Status](https://secure.travis-ci.org/suin/xoopsunit.png?branch=develop)](http://travis-ci.org/suin/xoopsunit)

XoopsUnit is a extension of PHPUnit. You can write simplier test code with XoopsUnit. 

## Features

* Revealing privacy of objects.
* Reporting untested methods automatically.

## Requirements

* PHP 5.3.0 or later

## Installing

Go to your project directory (There will be `html` and `xoops_trust_path`):

```sh
$ cd /path/to/your/xoops
```

And run this:

```sh
$ curl https://raw.github.com/gist/3116932/9577749ed6532d3ff6de9b9d1ea3f961ffa55dc7/xoopsunit-install.php -s -o xoopsunit-install.php && php xoopsunit-install.php && \rm xoopsunit-install.php
```

## Reference

### Revealing privacy

You can manipulate protected/private attributes of methods simply using ```reveal()```. 

```php
<?php

class RevealingSample1
{
	protected $bar = 'the best word is BAR';

	public function getBar()
	{
		return $this->bar;
	}

	protected function _foo()
	{
		return 'Is it possible to call me?';
	}
}

class RevealingSample1Test extends \XoopsUnit\TestCase
{
	public function testGetBar()
	{
		$foo = new RevealingSample1();
		$this->reveal($foo)->attr('bar', 'the best word is FOO'); // Simple to manipulate!!
		$this->assertSame('the best word is FOO', $foo->getBar());
	}

	public function test_foo()
	{
		$foo = new RevealingSample1();
		$actual = $this->reveal($foo)->call('_foo'); // Simple to call!!
		$this->assertSame('Is it possible to call me?', $actual);
	}
}
```

## APIs

* [\XoopsUnit\TestCase](https://github.com/suin/xoopsunit/blob/master/Source/XoopsUnit/TestCaseInterface.php)