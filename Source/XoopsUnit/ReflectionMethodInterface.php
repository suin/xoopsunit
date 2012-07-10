<?php

namespace XoopsUnit;

interface ReflectionMethodInterface
{
	/**
	 * Set public accessibility
	 * @return $this
	 */
	public function publicize();

	/**
	 * @param array $arguments
	 * @return mixed
	 */
	public function invokeArray(array $arguments);
}
