<?php

namespace XoopsUnit;

interface RevealInterface
{
	/**
	 * @param object $object
	 * @throws \InvalidArgumentException
	 */
	public function __construct($object);

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
