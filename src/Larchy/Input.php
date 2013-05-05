<?php

namespace Larchy;

class Input
{
	private $laravelInput;

	public function __construct( $laravelInput = 'Input' )
	{
		$this->laravelInput = $laravelInput;
	}

	public function get( $key = null )
	{
		$laravelInput = $this->laravelInput;

		return $laravelInput::get( $key );
	}
}