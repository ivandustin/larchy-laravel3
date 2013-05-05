<?php

namespace Larchy;

class Response
{
	private $laravelResponse;

	public function __construct( $laravelResponse = 'Response' )
	{
		$this->laravelResponse = $laravelResponse;
	}

	public function make( $content, $statusCode = 200, $headers = array() )
	{
		$laravelResponse = $this->laravelResponse;

		return $laravelResponse::make( $content, $statusCode, $headers );
	}

	public function json( $data, $statusCode = 200, $headers = array() )
	{
		$laravelResponse = $this->laravelResponse;

		return $laravelResponse::json( $data, $statusCode, $headers );
	}
}