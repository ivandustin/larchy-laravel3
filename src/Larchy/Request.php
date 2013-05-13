<?php

namespace Larchy;

class Request
{
	private $laravelUrl;
	private $laravelRequest;

	public function __construct( $laravelUrl = 'URL', $laravelRequest = 'Request' )
	{
		$this->laravelUrl = $laravelUrl;
		$this->laravelRequest = $laravelRequest;
	}

	public function url()
	{
		$laravelUrl = $this->laravelUrl;

		return $laravelUrl::full();
	}

	public function powerload()
	{
		$laravelRequest = $this->laravelRequest;

		$value = $laravelRequest::header('x-powerload', NULL);

		return $value !== NULL AND $value[0] === 'true' OR $value[0] === 'leaf-only' ? true : false;
	}

	public function leafOnly()
	{
		$laravelRequest = $this->laravelRequest;

		$value = $laravelRequest::header('x-powerload', NULL);

		return $value !== NULL AND $value[0] === 'leaf-only' ? true : false;
	}
}