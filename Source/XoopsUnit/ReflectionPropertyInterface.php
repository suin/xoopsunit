<?php

namespace XoopsUnit;

interface ReflectionPropertyInterface
{
	/**
	 * Set public accessibility
	 * @return $this
	 */
	public function publicize();

	/**
	 * Set value
	 * @param mixed $value
	 * @return $this
	 */
	public function setValue($value);
}
