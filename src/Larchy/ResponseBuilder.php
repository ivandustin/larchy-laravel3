<?php

namespace Larchy;

class ResponseBuilder
{
	private $site;
	private $request;
	private $urlparser;
	private $view;
	private $response;

	public function __construct( $site, $request, $urlparser, $view, $response )
	{
		$this->site = $site;
		$this->request = $request;
		$this->urlparser = $urlparser;
		$this->view = $view;
		$this->response = $response;
	}

	public function make( $data = array(), $statusCode = 200, $headers = array() )
	{
		if( $this->request->powerload() )
		{
			return $this->powerload( $data['title'], $statusCode, $headers );
		}

		return $this->normal( $data, $statusCode, $headers );
	}

	public function normal( $data = array(), $statusCode = 200, $headers = array() )
	{
		$baseUrl = $this->site->base();
		$requestUrl = $this->request->url();

		$result = $this->urlparser->stemleaf( $requestUrl, $baseUrl );
		$stem = $result['stem'];
		$leaf = $result['leaf'];

		$document = $this->view->make( $stem, $leaf, $data );

		return $this->response->make( $document, $statusCode, $headers );
	}

	public function powerload( $title, $statusCode = 200, $headers = array() )
	{
		$baseUrl = $this->site->base();
		$requestUrl = $this->request->url();

		$result = $this->urlparser->stemleaf($requestUrl, $baseUrl);
		$stem = $result['stem'];
		$leaf = $result['leaf'];

		$data = array('title' => $title);

		if( $this->request->leafOnly() )
		{
			$data['leaf'] = $this->view->makeLeaf($stem, $leaf);
		}
		else
		{
			$data['stem'] = $this->view->makeStem($stem, $leaf);
		}

		return $this->response->json($data, $statusCode, $headers);
	}

}