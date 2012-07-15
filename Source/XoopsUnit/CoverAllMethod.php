<?php

namespace XoopsUnit;

use \XoopsUnit\TestCaseInterface;
use \XoopsUnit\ReflectionClass;

class CoverAllMethod
{
	/**
	 * @param \XoopsUnit\TestCaseInterface $testCase
	 * @param string $testTarget
	 * @return array
	 */
	public function getUncoveredMethods(TestCaseInterface $testCase, $testTarget)
	{
		$uncoveredMethods = array();

		if ( $this->_testTargetExists($testTarget) === false )
		{
			return $uncoveredMethods;
		}

		$targetMethods    = $this->_getTestTargetMethods($testTarget);
		$testCaseMethods  = $this->_getTestCaseMethods($testCase);
		$uncoveredMethods = array_diff($targetMethods, $testCaseMethods);
		$uncoveredMethods = array_values($uncoveredMethods); // Reset indexes

		return $uncoveredMethods;
	}

	/**
	 *
	 * @param string $testTarget
	 * @return string[]
	 */
	protected function _getTestTargetMethods($testTarget)
	{
		$reflectionClass = new ReflectionClass($testTarget);

		/** @var $methods ReflectionMethod[] */
		$methods = $reflectionClass->getSelfMethods();
		$testMethods = array();

		foreach ( $methods as $method )
		{
			$methodName = $method->getName();

			if ( $method->isAbstract() === true )
			{
				continue;
			}

			if ( $method->isConstructor() === true )
			{
				$methodName = '__construct';
			}

			$testMethods[] = 'test'. ucfirst($methodName);
		}

		return $testMethods;
	}

	/**
	 * @param \XoopsUnit\TestCaseInterface $testCase
	 * @return string[]
	 */
	protected function _getTestCaseMethods(TestCaseInterface $testCase)
	{
		$reflectionClass = new ReflectionClass($testCase);
		$methods = $reflectionClass->getSelfMethods();
		$testCaseMethods = array();

		foreach ( $methods as $method )
		{
			$testCaseMethods[] = $method->getName();
		}

		return $testCaseMethods;
	}

	/**
	 * @param string $testTarget
	 * @return bool
	 */
	protected function _testTargetExists($testTarget)
	{
		if ( class_exists($testTarget) )
		{
			return true;
		}

		if ( interface_exists($testTarget) )
		{
			return true;
		}

		if ( function_exists('trait_exists') and trait_exists($testTarget) )
		{
			return true;
		}

		return false;
	}
}
