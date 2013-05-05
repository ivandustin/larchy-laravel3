<?php

namespace Larchy;

class UrlParser
{
	public function stemleaf( $url, $base )
	{
		// Prepare the urls
		$url = $this->clean( $url, 'querystring|endslash' );
		$base = $this->clean( $base, 'querystring|endslash' );


		// Begin parsing

		if( ! $this->isChild($url, $base) )
		{
			return false;
		}

		$stemleaf = array();
		
		if( strlen($url) === strlen($base) )
		{
			$stemleaf['stem'] = LARCHY_DEFAULT_STEM;
			$stemleaf['leaf'] = LARCHY_DEFAULT_LEAF;
		}
		else
		{
			$uri = substr( $url, strlen($base) + 1, strlen($url) ); // plus 1 for the slash infront

			$chunks = explode('/', $uri);
			if( count($chunks) === 1 )
			{
				$stemleaf['stem'] = $chunks[0];
				$stemleaf['leaf'] = LARCHY_DEFAULT_LEAF;
			}
			else
			{
				$stemleaf['stem'] = $chunks[0];
				$stemleaf['leaf'] = $chunks[1];
			}
		}

		return $stemleaf;
	}

	public function stem( $url, $base )
	{
		$stemleaf = $this->stemleaf( $url, $base );
		return $stemleaf['stem'];
	}

	public function leaf( $url, $base )
	{
		$stemleaf = $this->stemleaf( $url, $base );
		return $stemleaf['leaf'];
	}

	public function isChild( $url, $base )
	{
		// Clean the urls
		$url = $this->clean( $url , 'endslash|querystring|protocol' );
		$base = $this->Clean( $base , 'endslash|querystring|protocol' );

		// Parse
		if( strpos($url, $base) === 0 )
		{
			return true;
		}

		return false;
	}

	public function clean( $url, $filters )
	{
		// Define the filters
		$list = array(
			'endslash' => '/\/(?=\?)|\/$/',
			'protocol' => '/^[^\:]+\:\/\//',
			'querystring' => '/\?.*/'
		);

		$filters = explode( '|', $filters );

		foreach( $filters as $filter )
		{
			if( ! array_key_exists($filter, $list) )
				continue;

			$url = preg_replace( $list[$filter], '', $url );
		}

		return $url;
	}
}