<?php

namespace XoopsUnit;

class ReflectionMethod extends \ReflectionMethod implements \XoopsUnit\ReflectionMethodInterface
{
	protected $klass = null;

	/**
	 * Return new ReflectionMethod object
	 * @param string|object $class
	 * @param string $name
	 */
	public function __construct($class, $name)
	{
		$this->klass = $class;
		parent::__construct($class, $name);
	}

	/**
	 * Set public accessibility
	 * @return $this
	 */
	public function publicize()
	{
		if ( $this->isPublic() === false )
		{
			$this->setAccessible(true);
		}

		return $this;
	}
}
