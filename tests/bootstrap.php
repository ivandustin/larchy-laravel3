<?php

require '../src/Larchy/bootstrap.php';

spl_autoload_register(function($classname)
{
	$vendor = 'Larchy';
	$classname = ltrim($classname, '\\');

	if( strpos($classname, $vendor) === 0 )
	{
		$file = __DIR__ . '/../src/Larchy';
		$file .= substr( $classname, strlen($vendor), strlen($classname) );
		$file .= '.php';
	}
	else
	{
		return;
	}

	if( file_exists($file) )
	{
		require $file;
	}
});