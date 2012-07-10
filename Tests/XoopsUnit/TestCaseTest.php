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
		$testCaseClass = $this->getMockClass($this->testCaseClass, array(), array(), '', false);
		$testCase = new $testCaseClass();
		$this->assertNull($testCase->testCoverAllMethods());
	}
}
