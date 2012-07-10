<?php

namespace XoopsUnit;

interface TestCaseInterface
{
	const XOOPSUNIT_VERSION = '1.0';

	/**
	 * Return new Reveal object
	 * @param object $object
	 * @return \XoopsUnit\RevealInterface
	 */
	public function reveal($object);

	/**
	 * Mark the test as incomplete if there are some untested methods.
	 */
	public function testCoverAllMethods();
}
