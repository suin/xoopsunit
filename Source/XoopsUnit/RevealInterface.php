<?php

namespace XoopsUnit;

use \XoopsUnit\ReflectionClassInterface;

interface RevealInterface
{
	/**
	 * Return new Reveal object
	 * @param \XoopsUnit\ReflectionClassInterface $reflectionClass
	 */
	public function __construct(ReflectionClassInterface $reflectionClass);

	/**
	 * Modify the value of the protected/private property in the specified object
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function attr($name, $value);

	/**
	 * Call a protected method
	 * @param string $name
	 * @param mixed* $arguments
	 * @return mixed
	 */
	public function call($name);
}
