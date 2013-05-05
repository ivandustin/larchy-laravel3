<?php

namespace Larchy;

class ResponseBuilder
{
	private $input;
	private $site;
	private $request;
	private $urlparser;
	private $view;
	private $response;

	public function __construct( $input, $site, $request, $urlparser, $view, $response )
	{
		$this->input = $input;
		$this->site = $site;
		$this->request = $request;
		$this->urlparser = $urlparser;
		$this->view = $view;
		$this->response = $response;
	}

	public function make( $data = array(), $statusCode = 200, $headers = array() )
	{
		$baseUrl = $this->site->base();
		$referrer = $this->request->referrer();

		if( $this->input->get('powerload') === 'true' AND $referrer !== null AND $this->urlparser->isChild($referrer, $baseUrl) )
		{
			return $this->powerload( $data['title'], $statusCode, $headers );
		}

		return $this->normal( $data, $statusCode, $headers );
	}

	public function normal( $data = array(), $statusCode = 200, $headers = array() )
	{
		$baseUrl = $this->site->base();
		$requestUrl = $this->request->url();

		$stemleaf = $this->urlparser->stemleaf( $requestUrl, $baseUrl );
		$stem = $stemleaf['stem'];
		$leaf = $stemleaf['leaf'];

		$document = $this->view->make( $stem, $leaf, $data );

		return $this->response->make( $document, $statusCode, $headers );
	}

	public function powerload( $title, $statusCode = 200, $headers = array() )
	{
		$baseUrl = $this->site->base();
		$requestUrl = $this->request->url();
		$referrer = $this->request->referrer();

		$stemleaf = $this->urlparser->stemleaf( $requestUrl, $baseUrl );
		$stem = $stemleaf['stem'];
		$leaf = $stemleaf['leaf'];
		$referrerStem = $this->urlparser->stem( $referrer, $baseUrl );

		$data['title'] = $title;

		if( $stem === $referrerStem )
		{
			$data['leaf'] = $this->view->makeLeaf( $stem, $leaf );
		}
		else
		{
			$data['stem'] = $this->view->makeStem( $stem, $leaf );
		}

		return $this->response->json( $data, $statusCode, $headers );
	}

}