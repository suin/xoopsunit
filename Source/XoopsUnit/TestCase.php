<?php

namespace XoopsUnit;

use \XoopsUnit\Reveal;
use \XoopsUnit\CoverAllMethod;
use \XoopsUnit\ReflectionClass;

class TestCase extends \PHPUnit_Framework_TestCase
               implements \XoopsUnit\TestCaseInterface,
                          \XoopsUnit\AssertInterface
{
	/**
	 * Return new Reveal object
	 * @param object $object
	 * @return \XoopsUnit\RevealInterface
	 */
	public function reveal($object)
	{
		return new Reveal(new ReflectionClass($object));
	}

	/**
	 * Mark the test as incomplete if there are some untested methods.
	 */
	public function testCoverAllMethods()
	{
		$testTarget = preg_replace('/Test$/', '', get_class($this));
		$cover = new CoverAllMethod();
		$uncoveredMethods = $cover->getUncoveredMethods($this, $testTarget);

		if ( count($uncoveredMethods) > 0 )
		{
			$this->markTestIncomplete(sprintf("This test case is missing tests for class: %s\n  * %s()", $testTarget, implode("()\n  * ", $uncoveredMethods)));
		}
	}
}
