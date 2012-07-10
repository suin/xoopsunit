<?php

if ( version_compare(PHP_VERSION, '5.3.0', '<') )
{
	echo "This tests requires PHP 5.3.0 or later", PHP_EOL;
	exit(1);
}

require_once __DIR__.'/Bootstrap/ClassAutoLoad.php';
