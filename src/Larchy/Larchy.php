<?php

namespace Larchy;

class Larchy
{
	private $responseBuilder;

	public function __construct( $responseBuilder = null )
	{
		if( $responseBuilder === null )
		{
			$responseBuilder = new ResponseBuilder(new Site(), new Request(), new UrlParser(), new View(), new Response());
		}

		$this->responseBuilder = $responseBuilder;
	}

	public function make( $data = array(), $statusCode = 200, $headers = array() )
	{
		if( is_string($data) )
		{
			$title = $data;
			
			$data = array(
				'title' => $title
			);
		}

		return $this->responseBuilder->make( $data, $statusCode, $headers );
	}
}