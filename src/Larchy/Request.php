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

	public function referrer()
	{
		$laravelRequest = $this->laravelRequest;

		return $laravelRequest::referrer();
	}
}