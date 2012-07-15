<?php

namespace XoopsUnit;

class TestCaseTest extends \XoopsUnit\TestCase
{
	protected $testCaseClass = '\XoopsUnit\TestCase';
	protected $revealClass = '\XoopsUnit\Reveal';
	protected $coverAllMethodClass = '\XoopsUnit\CoverAllMethod';

	/**
	 * @return \XoopsUnit\TestCase
	 */
	public function newTestCase()
	{
		return $this
			->getMockBuilder($this->testCaseClass)
			->setMethods(null)
			->disableOriginalConstructor()
			->getMock();
	}

	public function testReveal()
	{
		$testCase = $this->newTestCase();
		$this->assertInstanceOf($this->revealClass, $testCase->reveal(new \stdClass()));
	}

	public function testTestCoverAllMethods()
	{
		$className = 'Test' . __FUNCTION__ . uniqid();

		$code = '
			class __CLASS__ extends __PARENT__
			{
				public static $testCase;
				public static $expectedMessage;
				public static function markTestIncomplete($message)
				{
					self::$testCase->assertSame(self::$expectedMessage, $message);
				}
			}'
		;

		$code = str_replace('__CLASS__', $className, $code);
		$code = str_replace('__PARENT__', $this->testCaseClass, $code);

		eval($code);

		$className::$testCase = $this;
		$className::$expectedMessage = "This test case is missing tests for class: {$className}\n  * testMarkTestIncomplete()";
		$testCase = new $className();
		$this->assertNull($testCase->testCoverAllMethods());
	}
}
