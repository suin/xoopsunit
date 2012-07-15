<?php

namespace XoopsUnit;

class ReflectionMethodTest extends \XoopsUnit\TestCase
{
	protected $reflectionMethodClass = '\XoopsUnit\ReflectionMethod';

	public function test__construct()
	{
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { protected function foo(){ } }', $className));
		$reflectionMethod = new ReflectionMethod($className, 'foo');
		$this->assertAttributeSame($className, 'klass', $reflectionMethod);
	}

	public function testPublicize()
	{
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { protected function foo() { return true; } }', $className));
		$object = new $className();

		$reflectionMethod = new ReflectionMethod($object, 'foo');
		$this->assertTrue($reflectionMethod->isProtected());
		$this->assertFalse($reflectionMethod->isStatic());
		$this->assertSame($reflectionMethod, $reflectionMethod->publicize());
		$this->assertSame(true, $reflectionMethod->invoke($object));
	}

	public function testPublicize_with_private_method()
	{
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { private function foo() { return true; } }', $className));
		$object = new $className();

		$reflectionMethod = new ReflectionMethod($object, 'foo');
		$this->assertTrue($reflectionMethod->isPrivate());
		$this->assertFalse($reflectionMethod->isStatic());
		$this->assertSame($reflectionMethod, $reflectionMethod->publicize());
		$this->assertSame(true, $reflectionMethod->invoke($object));
	}

	public function testPublicize_with_protected_static_method()
	{
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { protected static function foo() { return true; } }', $className));

		$reflectionMethod = new ReflectionMethod($className, 'foo');
		$this->assertTrue($reflectionMethod->isProtected());
		$this->assertTrue($reflectionMethod->isStatic());
		$this->assertSame($reflectionMethod, $reflectionMethod->publicize());
		$this->assertSame(true, $reflectionMethod->invoke($className));
	}

	public function testPublicize_with_private_static_method()
	{
		$className = __FUNCTION__ . md5(uniqid());
		eval(sprintf('class %s { private static function foo() { return true; } }', $className));

		$reflectionMethod = new ReflectionMethod($className, 'foo');
		$this->assertTrue($reflectionMethod->isPrivate());
		$this->assertTrue($reflectionMethod->isStatic());
		$this->assertSame($reflectionMethod, $reflectionMethod->publicize());
		$this->assertSame(true, $reflectionMethod->invoke($className));
	}

	public function testInvokeArray()
	{
		$className = 'className';
		$arg1 = new \stdClass();
		$arg2 = new \stdClass();
		$returnValue = new \stdClass();

		$reflectionMethod = $this
			->getMockBuilder($this->reflectionMethodClass)
			->disableOriginalConstructor()
			->setMethods(array('invoke'))
			->getMock();
		$reflectionMethod
			->expects($this->once())
			->method('invoke')
			->with($className, $arg1, $arg2)
			->will($this->returnValue($returnValue));

		$this->reveal($reflectionMethod)->attr('klass', $className);
		$actual = $reflectionMethod->invokeArray(array($arg1, $arg2));
		$this->assertSame($returnValue, $actual);
	}
}
