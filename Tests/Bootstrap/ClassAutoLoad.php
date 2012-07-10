<?php

$projectRootDir = dirname(dirname(__DIR__));

// Register PSR-0 compatible class autoloader
spl_autoload_register(function($c) { @include_once strtr($c, '\\_', '//').'.php'; });

// Register PATHs
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, array(
	$projectRootDir.'/Source',
)));

