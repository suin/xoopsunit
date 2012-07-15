<?php

namespace XoopsUnit;

class RevealTest extends \XoopsUnit\TestCase
{
	protected $revealClass = '\XoopsUnit\Reveal';

	public function getMockBuilderForReveal()
	{
		return $this
			->getMockBuilder($this->revealClass)
			->disableOriginalConstructor()
			->setMethods(null);
	}

	public function newRevealMock()
	{
		return $this
			->getMockBuilderForReveal()
			->getMock();
	}

	public function test__construct()
	{
		$reflectionClass = $this->getMock('\XoopsUnit\ReflectionClassInterface');
		$reveal = new Reveal($reflectionClass);
		$this->assertAttributeSame($reflectionClass, 'reflectionClass', $reveal);
	}

	public function testAttr()
	{
		$name  = 'attribute name';
		$value = new \stdClass();

		$reflectionClass = $this->getMock('stdClass', array(
			'property',
			'publicize',
			'setValue',
		));
		$reflectionClass
			->expects($this->at(0))
			->method('property')
			->with($name)
			->will($this->returnSelf());
		$reflectionClass
			->expects($this->at(1))
			->method('publicize')
			->will($this->returnSelf());
		$reflectionClass
			->expects($this->at(2))
			->method('setValue')
			->with($value);

		$reveal = $this->newRevealMock();

		$this->reveal($reveal)->attr('reflectionClass', $reflectionClass);
		$this->assertSame($reveal, $reveal->attr($name, $value));
	}

	public function testCall()
	{
		$name = 'method_name';
		$argument1 = new \stdClass();
		$argument2 = new \stdClass();
		$arguments = array($argument1, $argument2);
		$result = new \stdClass();

		$reflectionClass = $this->getMock('stdClass', array(
			'method',
			'publicize',
			'invokeArray',
		));
		$reflectionClass
			->expects($this->at(0))
			->method('method')
			->with($name)
			->will($this->returnSelf());
		$reflectionClass
			->expects($this->at(1))
			->method('publicize')
			->will($this->returnSelf());
		$reflectionClass
			->expects($this->at(2))
			->method('invokeArray')
			->with($arguments)
			->will($this->returnValue($result));

		$reveal = $this->newRevealMock();

		$this->reveal($reveal)->attr('reflectionClass', $reflectionClass);
		$this->assertSame($result, $reveal->call($name, $argument1, $argument2));
	}
}
