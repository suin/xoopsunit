<?php

namespace XoopsUnit;

use \XoopsUnit\ReflectionProperty;
use \XoopsUnit\ReflectionMethod;

class ReflectionClass extends \ReflectionClass implements \XoopsUnit\ReflectionClassInterface
{
	protected $klass = null;

	public function __construct($argument)
	{
		parent::__construct($argument);
		$this->klass = $argument;
	}

	/**
	 * @param string $name
	 * @return \XoopsUnit\ReflectionPropertyInterface
	 */
	public function property($name)
	{
		return new ReflectionProperty($this->klass, $name);
	}

	/**
	 * @param string $name
	 * @return \XoopsUnit\ReflectionMethodInterface
	 */
	public function method($name)
	{
		return new ReflectionMethod($this->klass, $name);
	}

	/**
	 * Gets a list of methods which are defined in the self class
	 * @return ReflectionMethod[]
	 */
	public function getSelfMethods()
	{
		$myName     = $this->getName();
		$myFilename = $this->getFileName();
		$hasTrait   = $this->usesTraits();
		$methods    = $this->getMethods();

		/** @var ReflectionMethod $method */
		foreach ( $methods as $key => $method )
		{
			$thatName     = $method->getDeclaringClass()->getName();
			$thatFilename = $method->getFileName();

			if ( $myName != $thatName )
			{
				unset($methods[$key]);
			}
			else
			{
				if ( $hasTrait === true and $myFilename != $thatFilename )
				{
					unset($methods[$key]);
				}
			}
		}

		return $methods;
	}

	/**
	 * Returns an array of traits used by this class
	 * @return array with trait names in keys and instances of trait's ReflectionClass in values. Returns NULL in case of an error.
	 */
	public function getTraits()
	{
		$traits = array();

		if ( method_exists('\ReflectionClass', 'getTraits') )
		{
			$traits = parent::getTraits();
		}

		return $traits;
	}

	/**
	 * Determine if this class uses traits
	 * @return bool
	 */
	public function usesTraits()
	{
		if ( count($this->getTraits()) > 0 )
		{
			return true;
		}

		return false;
	}
}
