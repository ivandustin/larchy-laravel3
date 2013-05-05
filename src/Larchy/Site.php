<?php

namespace Larchy;

class Site
{

	private $laravelUrl;

	public function __construct( $laravelUrl = 'URL' )
	{
		$this->laravelUrl = $laravelUrl;
	}

	public function base()
	{
		$laravelUrl = $this->laravelUrl;

		return $laravelUrl::base();
	}

}