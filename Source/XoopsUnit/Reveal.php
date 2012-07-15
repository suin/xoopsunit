<?php

namespace XoopsUnit;

use \InvalidArgumentException;
use \XoopsUnit\ReflectionClassInterface;

class Reveal implements \XoopsUnit\RevealInterface
{
	/** @var \XoopsUnit\ReflectionClassInterface */
	protected $reflectionClass = null;

	/**
	 * Return new Reveal object
	 * @param \XoopsUnit\ReflectionClassInterface $reflectionClass
	 */
	public function __construct(ReflectionClassInterface $reflectionClass)
	{
		$this->reflectionClass = $reflectionClass;
	}

	/**
	 * Modify the value of the protected/private property in the specified object
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function attr($name, $value)
	{
		$this->reflectionClass->property($name)->publicize()->setValue($value);
		return $this;
	}

	/**
	 * Call a protected method
	 * @param string $name
	 * @param mixed* $arguments
	 * @return mixed
	 */
	public function call($name)
	{
		$arguments = func_get_args();
		$name = array_shift($arguments);
		return $this->reflectionClass->method($name)->publicize()->invokeArray($arguments);
	}
}
