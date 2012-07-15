<?php

namespace XoopsUnit;

class CoverAllMethodTest extends \XoopsUnit\TestCase
{
	protected $coverAllMethodClass = '\XoopsUnit\CoverAllMethod';
	protected $testCaseInterface = '\XoopsUnit\TestCaseInterface';

	public function getMockBuilderForCoverAllMethod()
	{
		return $this
			->getMockBuilder($this->coverAllMethodClass)
			->setMethods(null);
	}

	public function newCoverAllMethodMock()
	{
		return $this
			->getMockBuilderForCoverAllMethod()
			->getMock();
	}

	public function testGetUncoveredMethods()
	{
		$testCase = $this->getMock($this->testCaseInterface);
		$testTarget = 'TestTarget';

		$coverAllMethod = $this
			->getMockBuilderForCoverAllMethod()
			->setMethods(array(
				'_testTargetExists',
				'_getTestTargetMethods',
				'_getTestCaseMethods',
			))
			->getMock();
		$coverAllMethod
			->expects($this->at(0))
			->method('_testTargetExists')
			->with($testTarget)
			->will($this->returnValue(false));
		$coverAllMethod
			->expects($this->never())
			->method('_getTestTargetMethods');
		$coverAllMethod
			->expects($this->never())
			->method('_getTestCaseMethods');
		$expect = array();
		$actual = $coverAllMethod->getUncoveredMethods($testCase, $testTarget);
		$this->assertSame($expect, $actual);
	}

	public function testGetUncoveredMethods_with_test_target_exists()
	{
		$testCase = $this->getMock($this->testCaseInterface);
		$testTarget = 'TestTarget';
		$targetMethods    = array('foo', 'bar');
		$testCaseMethods  = array('foo', 'baz');
		$uncoveredMethods = array('bar');

		$coverAllMethod = $this
			->getMockBuilderForCoverAllMethod()
			->setMethods(array(
			'_testTargetExists',
			'_getTestTargetMethods',
			'_getTestCaseMethods',
		))
			->getMock();
		$coverAllMethod
			->expects($this->at(0))
			->method('_testTargetExists')
			->with($testTarget)
			->will($this->returnValue(true));
		$coverAllMethod
			->expects($this->at(1))
			->method('_getTestTargetMethods')
			->with($testTarget)
			->will($this->returnValue($targetMethods));
		$coverAllMethod
			->expects($this->at(2))
			->method('_getTestCaseMethods')
			->with($testCase)
			->will($this->returnValue($testCaseMethods));

		$actual = $coverAllMethod->getUncoveredMethods($testCase, $testTarget);
		$this->assertSame($uncoveredMethods, $actual);
	}

	/**
	 * @param $expect
	 * @param $classDefinition
	 * @dataProvider dataForTest_getTestTargetMethods
	 */
	public function test_getTestTargetMethods($expect, $classDefinition)
	{
		// Test case: no methods
		$className = __FUNCTION__.uniqid();
		eval(str_replace('__CLASS__', $className, $classDefinition));
		$coverAllMethod = new CoverAllMethod();
		$actual = $this->reveal($coverAllMethod)->call('_getTestTargetMethods', $className);
		$this->assertSame($expect, $actual);
	}

	public static function dataForTest_getTestTargetMethods()
	{
		return array(
			array(
				array(),
				'class __CLASS__ {}',
			),
			// with normal methods
			array(
				array('testFoo', 'test_bar', 'test_baz', 'testFooStatic', 'test_barStatic', 'test_bazStatic'),
				'class __CLASS__ {
					public function foo() {}
					protected function _bar() {}
					private function _baz() {}
					public static function fooStatic() {}
					protected static function _barStatic() {}
					private static function _bazStatic() {}
				}',
			),
			// with abstract methods
			array(
				array(),
				'abstract class __CLASS__ {
					abstract public function foo();
					abstract protected function _bar();
				}',
			),
			// with PHP 4 style constructor
			array(
				array('test__construct'),
				'class __CLASS__ {
					public function __CLASS__() {}
				}',
			),
		);
	}

	public function test_getTestCaseMethods()
	{
		$testCase = $this->getMockForAbstractClass($this->testCaseInterface);
		$reflectionClass = new \XoopsUnit\ReflectionClass($testCase);
		$methods = $reflectionClass->getSelfMethods();
		$methods = array_map(function(\ReflectionMethod $method){
			return $method->getName();
		}, $methods);

		$expect = $methods;
		$coverAllMethod = new CoverAllMethod();
		$actual = $this->reveal($coverAllMethod)->call('_getTestCaseMethods', $testCase);
		$this->assertSame($expect, $actual);
	}

	public function test_testTargetExists()
	{
		$className = __FUNCTION__.uniqid();
		eval(sprintf('class %s {}', $className));
		$coverAllMethod = new CoverAllMethod();
		$actual = $this->reveal($coverAllMethod)->call('_testTargetExists', $className);
		$this->assertTrue($actual);
	}

	public function test_testTargetExists_with_interface()
	{
		$className = __FUNCTION__.uniqid();
		eval(sprintf('interface %s {}', $className));
		$coverAllMethod = new CoverAllMethod();
		$actual = $this->reveal($coverAllMethod)->call('_testTargetExists', $className);
		$this->assertTrue($actual);
	}

	/**
	 * @requires PHP 5.4.0
	 */
	public function test_testTargetExists_with_trait()
	{
		$className = __FUNCTION__.uniqid();
		eval(sprintf('trait %s {}', $className));
		$coverAllMethod = new CoverAllMethod();
		$actual = $this->reveal($coverAllMethod)->call('_testTargetExists', $className);
		$this->assertTrue($actual);
	}

	public function test_testTargetExists_with_non_exsiting_class()
	{
		$className = __FUNCTION__.uniqid();
		$coverAllMethod = new CoverAllMethod();
		$actual = $this->reveal($coverAllMethod)->call('_testTargetExists', $className);
		$this->assertFalse($actual);
	}
}
