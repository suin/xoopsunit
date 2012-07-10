<?php

namespace XoopsUnit;

use \InvalidArgumentException;
use \XoopsUnit\ReflectionClass;

class Reveal implements \XoopsUnit\RevealInterface
{
	protected $originalObject = null;

	/** @var \XoopsUnit\ReflectionClass */
	protected $object = null;

	/**
	 * @param object $object
	 * @throws \InvalidArgumentException
	 */
	public function __construct($object)
	{
		if ( is_object($object) === false )
		{
			throw new InvalidArgumentException('Not object was given.');
		}

		$this->originalObject = $object;
		$this->object = new ReflectionClass($object);
	}

	/**
	 * Modify the value of the protected/private property in the specified object
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function attr($name, $value)
	{
		$this->object->property($name)->publicize()->setValue($value);
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
		return $this->object->method($name)->publicize()->invokeArray($arguments);
	}
}
