<?php

require_once __DIR__ . '/Larchy/bootstrap.php';

class Larchy
{
	private function __construct() {}

	public static function getInstance()
	{
		static $inst = NULL;

		if( $inst === NULL )
		{
			$inst = new \Larchy\Larchy();
		}

		return $inst;
	}

	public static function __callStatic( $name, $arguments )
	{
		$larchy = self::getInstance();

		return call_user_func_array( array($larchy, $name), $arguments );
	}
}