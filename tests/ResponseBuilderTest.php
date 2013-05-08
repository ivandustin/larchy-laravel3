<?php

class ResponseBuilderTest extends PHPUnit_Framework_TestCase
{

	public $objects;

	public function setUp()
	{
		// Dependencies
		$site = Mockery::mock('Site');
		$request = Mockery::mock('Request');
		$urlParser = Mockery::mock('UrlParser');
		$view = Mockery::mock('View');
		$response = Mockery::mock('Response');

		// Subject
		$responseBuilder = Mockery::mock('Larchy\ResponseBuilder', array($site, $request, $urlParser, $view, $response))->makePartial();

		// Pass all objects to method-local variable
		$this->objects = array(
			'site' => $site,
			'request' => $request,
			'urlParser' => $urlParser,
			'view' => $view,
			'response' => $response,
			'responseBuilder' => $responseBuilder
		);
	}

	public function tearDown()
	{
		Mockery::close();
	}

	public function test_make_method()
	{
		extract($this->objects);

		$data = array(
			'title' => 'page title',
			'meta' => array(
				'name' => 'content'
			)
		);
		$statusCode = 300;
		$headers = array('a' => 'b');

		# Should work on normal page load
		$request->shouldReceive('powerload')->once()->andReturn(false);
		$responseBuilder->shouldReceive('normal')->once()->with($data, $statusCode, $headers)->andReturn('Response Object');
		$result = $responseBuilder->make($data, $statusCode, $headers);
		$this->assertEquals('Response Object', $result);

		# Should work on powerload request
		$request->shouldReceive('powerload')->once()->andReturn(true);
		$responseBuilder->shouldReceive('powerload')->once()->with($data['title'], $statusCode, $headers)->andReturn('JSON Response Object');
		$result = $responseBuilder->make($data, $statusCode, $headers);
		$this->assertEquals('JSON Response Object', $result);
	}

	public function test_normal_method()
	{
		extract($this->objects);

		$data = array(
			'title' => 'hello world',
			'meta' => array('name' => 'content')
		);
		$statusCode = 400;
		$headers = array('a' => 'b');

		# Should work normally
		$site->shouldReceive('base')->once()->andReturn('Site Base URL');
		$request->shouldReceive('url')->once()->andReturn('Current URL');
		$urlParser->shouldReceive('stemleaf')->once()->with('Current URL', 'Site Base URL')->andReturn(array('stem' => 'A', 'leaf' => '1'));
		$view->shouldReceive('make')->once()->with('A', '1', $data)->andReturn('Full Page Document');
		$response->shouldReceive('make')->once()->with('Full Page Document', $statusCode, $headers)->andReturn('Response Object');
		$result = $responseBuilder->normal($data, $statusCode, $headers);
		$this->assertEquals('Response Object', $result);
	}

	public function test_powerload_method()
	{
		extract($this->objects);

		$pageTitle = 'Example Page';
		$statusCode = 300;
		$headers = array('field' => 'value');

		# Should work normally
		$site->shouldReceive('base')->once()->andReturn('Site Base URL');
		$request->shouldReceive('url')->once()->andReturn('Current URL');
		$urlParser->shouldReceive('stemleaf')->once()->with('Current URL', 'Site Base URL')->andReturn(array('stem' => 'A', 'leaf' => '1'));
		$request->shouldReceive('leafOnly')->once()->andReturn(false);
		$view->shouldReceive('makeStem')->once()->with('A', '1')->andReturn('HTML Stem + Leaf');
		$response->shouldReceive('json')->once()->with(array('title' => $pageTitle, 'stem' => 'HTML Stem + Leaf'), $statusCode, $headers)->andReturn('JSON Response Object');
		$result = $responseBuilder->powerload($pageTitle, $statusCode, $headers);
		$this->assertEquals('JSON Response Object', $result);

		# Should work on leaf only request
		$site->shouldReceive('base')->once()->andReturn('Site Base URL');
		$request->shouldReceive('url')->once()->andReturn('Current URL');
		$urlParser->shouldReceive('stemleaf')->once()->with('Current URL', 'Site Base URL')->andReturn(array('stem' => 'A', 'leaf' => '1'));
		$request->shouldReceive('leafOnly')->once()->andReturn(true);
		$view->shouldReceive('makeLeaf')->once()->with('A', '1')->andReturn('HTML Leaf Only');
		$response->shouldReceive('json')->once()->with(array('title' => $pageTitle, 'leaf' => 'HTML Leaf Only'), $statusCode, $headers)->andReturn('JSON Response Object');
		$result = $responseBuilder->powerload($pageTitle, $statusCode, $headers);
		$this->assertEquals('JSON Response Object', $result);
	}

}