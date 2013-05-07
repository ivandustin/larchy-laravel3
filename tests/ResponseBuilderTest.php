<?php

class ResponseBuilderTest extends PHPUnit_Framework_TestCase
{

	public $objects;

	public function setUp()
	{
		// Dependencies
		$input = Mockery::mock('Input');
		$site = Mockery::mock('Site');
		$request = Mockery::mock('Request');
		$urlParser = Mockery::mock('UrlParser');
		$view = Mockery::mock('View');
		$response = Mockery::mock('Response');

		// Subject
		$responseBuilder = Mockery::mock('Larchy\ResponseBuilder', array($input, $site, $request, $urlParser, $view, $response))->makePartial();

		// Pass all objects to method-local variable
		$this->objects = array(
			'input' => $input,
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
	
	public function test_make_method_on_normal_page_load()
	{
		extract( $this->objects );

		$data = array(
			'title' => 'page title',
			'meta' => array(
				'name' => 'content'
			)
		);
		$statusCode = 300;
		$headers = array('a' => 'b');

		$site->shouldReceive('base')->once()->andReturn('baseUrl');

		$request->shouldReceive('referrer')->once()->andReturn(null);

		$input->shouldReceive('get')->once()->with('powerload')->andReturn(null);

		$urlParser->shouldReceive('isChild')->never();

		$responseBuilder->shouldReceive('normal')->once()->with($data, $statusCode, $headers)->andReturn('responseObject');

		$result = $responseBuilder->make( $data, $statusCode, $headers );

		$this->assertEquals( 'responseObject', $result );
	}

	public function test_make_method_on_powerload_request()
	{
		extract( $this->objects );

		$data = array(
			'title' => 'page title',
			'meta' => array(
				'name' => 'content'
			)
		);
		$statusCode = 300;
		$headers = array('a' => 'b');

		$site->shouldReceive('base')->once()->andReturn('baseUrl');

		$request->shouldReceive('referrer')->once()->andReturn('referrerUrl');

		$input->shouldReceive('get')->once()->with('powerload')->andReturn('true');

		$urlParser->shouldReceive('isChild')->once()->with('referrerUrl', 'baseUrl')->andReturn(true);

		$responseBuilder->shouldReceive('powerload')->once()->with($data['title'], $statusCode, $headers)->andReturn('responseObject');

		$result = $responseBuilder->make( $data, $statusCode, $headers );

		$this->assertEquals( 'responseObject', $result );
	}

	public function test_make_method_on_powerload_request_but_the_referrer_is_not_a_child()
	{
		extract( $this->objects );

		$data = array(
			'title' => 'page title',
			'meta' => array(
				'name' => 'content'
			)
		);
		$statusCode = 300;
		$headers = array('a' => 'b');

		$site->shouldReceive('base')->once()->andReturn('baseUrl');

		$request->shouldReceive('referrer')->once()->andReturn('externalUrl');

		$input->shouldReceive('get')->once()->with('powerload')->andReturn('true');

		$urlParser->shouldReceive('isChild')->once()->with('externalUrl', 'baseUrl')->andReturn(false);

		$responseBuilder->shouldReceive('normal')->once()->with($data, $statusCode, $headers)->andReturn('responseObject');

		$result = $responseBuilder->make( $data, $statusCode, $headers );

		$this->assertEquals( 'responseObject', $result );
	}

	public function test_normal_method()
	{
		extract( $this->objects );

		$data = array(
			'title' => 'hello world',
			'meta' => array('name' => 'content')
		);
		$statusCode = 400;
		$headers = array('a' => 'b');

		$site->shouldReceive('base')->once()->andReturn('baseURL');

		$request->shouldReceive('url')->once()->andReturn('currentUrl');

		$stemleaf = array(
			'stem' => 'imStem',
			'leaf' => 'imLeaf'
		);
		$urlParser->shouldReceive('stemleaf')->once()->with('currentUrl', 'baseURL')->andReturn( $stemleaf );

		$view->shouldReceive('make')->once()->with('imStem', 'imLeaf', $data)->andReturn('html');

		$response->shouldReceive('make')->once()->with('html', $statusCode, $headers)->andReturn('responseObject');

		$result = $responseBuilder->normal( $data, $statusCode, $headers );

		$this->assertEquals( 'responseObject', $result );
	}

	public function test_powerload_method_with_different_stem()
	{
		extract( $this->objects );

		$title = 'page title';
		$statusCode = 400;
		$headers = array('a' => 'b');

		$site->shouldReceive('base')->once()->andReturn('baseUrl');

		$request->shouldReceive('url')->once()->andReturn('currentUrl');
		$request->shouldReceive('referrer')->once()->andReturn('referrer');

		$urlParser->shouldReceive('stemleaf')->with('currentUrl', 'baseUrl')->andReturn( array('stem' => 'newStem', 'leaf' => 'newLeaf') );
		$urlParser->shouldReceive('stemleaf')->with('referrer', 'baseUrl')->andReturn( array('stem' => 'oldStem', 'leaf' => 'oldLeaf') );

		$view->shouldReceive('makeStem')->once()->with('newStem', 'newLeaf')->andReturn('html stem with leaf');

		$response->shouldReceive('json')->once()->with(array('title' => $title, 'stem' => 'html stem with leaf'), $statusCode, $headers)->andReturn('responseObject');		

		$result = $responseBuilder->powerload( $title, $statusCode, $headers );

		$this->assertEquals( 'responseObject', $result);
	}
	
	public function test_powerload_method_with_same_stem_but_different_leaf()
	{
		extract( $this->objects );

		$title = 'page title';
		$statusCode = 400;
		$headers = array('a' => 'b');

		$site->shouldReceive('base')->once()->andReturn('baseUrl');

		$request->shouldReceive('url')->once()->andReturn('currentUrl');
		$request->shouldReceive('referrer')->once()->andReturn('referrer');

		$urlParser->shouldReceive('stemleaf')->with('currentUrl', 'baseUrl')->andReturn( array('stem' => 'oldStem', 'leaf' => 'newLeaf') );
		$urlParser->shouldReceive('stemleaf')->with('referrer', 'baseUrl')->andReturn( array('stem' => 'oldStem', 'leaf' => 'oldLeaf') );

		$view->shouldReceive('makeStem')->never();
		$view->shouldReceive('makeLeaf')->once()->with('oldStem', 'newLeaf')->andReturn('html leaf');

		$response->shouldReceive('json')->once()->with(array('title' => $title, 'leaf' => 'html leaf'), $statusCode, $headers)->andReturn('responseObject');

		$result = $responseBuilder->powerload( $title, $statusCode, $headers );

		$this->assertEquals( 'responseObject', $result);
	}

	public function test_powerload_method_with_same_stem_and_leaf()
	{
		extract( $this->objects );

		$title = 'page title';
		$statusCode = 400;
		$headers = array('a' => 'b');

		$site->shouldReceive('base')->once()->andReturn('baseUrl');

		$request->shouldReceive('url')->once()->andReturn('currentUrl');
		$request->shouldReceive('referrer')->once()->andReturn('referrer');

		$urlParser->shouldReceive('stemleaf')->with('currentUrl', 'baseUrl')->andReturn( array('stem' => 'oldStem', 'leaf' => 'oldLeaf') );
		$urlParser->shouldReceive('stemleaf')->with('referrer', 'baseUrl')->andReturn( array('stem' => 'oldStem', 'leaf' => 'oldLeaf') );

		$view->shouldReceive('makeStem')->never();
		$view->shouldReceive('makeLeaf')->never();

		$response->shouldReceive('json')->once()->with(NULL, $statusCode, $headers)->andReturn('responseObject');

		$result = $responseBuilder->powerload( $title, $statusCode, $headers );

		$this->assertEquals( 'responseObject', $result);
	}

}