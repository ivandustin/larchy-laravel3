<?php

class RequestTest extends PHPUnit_Framework_TestCase
{
	public $objects;

	public function setUp()
	{
		// Dependencies
		$laravelUrl = Mockery::mock('URL');
		$laravelRequest = Mockery::mock('Request');

		// Subject
		$request = Mockery::mock('Larchy\Request', array($laravelUrl, $laravelRequest))->makePartial();

		$o['laravelRequest'] = $laravelRequest;
		$o['laravelUrl'] = $laravelUrl;
		$o['request'] = $request;
		$this->objects = $o;
	}

	public function tearDown()
	{
		Mockery::close();
	}

	public function test_url_method()
	{
		extract($this->objects);

		# Should work normally
		$laravelUrl->shouldReceive('full')->once()->andReturn('http://www.domain.com/mysite/');
		$result = $request->url();
		$this->assertEquals( 'http://www.domain.com/mysite/', $result );
	}

	public function test_powerload_method()
	{
		extract($this->objects);

		$header = $laravelRequest->shouldReceive('header')->with('x-powerload', NULL);

		# Should return 'true' if X-Powerload header is set to 'true'
		$header->andReturn(array('true'));
		$this->assertEquals( true, $request->powerload() );

		# Should return 'true' if X-Powerload header is set to 'leaf-only'
		$header->andReturn(array('leaf-only'));
		$this->assertEquals( true, $request->powerload() );

		# Should return 'false' if X-Powerload header is not set
		$header->andReturn(NULL);
		$this->assertEquals( false, $request->powerload() );

		# Should return 'false' if X-Powerload header value is malformed
		$header->andReturn(array('true blablabla'));
		$this->assertEquals( false, $request->powerload() );
	}

	public function test_leafOnly_method()
	{
		extract($this->objects);

		$header = $laravelRequest->shouldReceive('header')->with('x-powerload', NULL);

		# Should return 'true' if X-Powerload header value is set to 'leaf-only'
		$header->andReturn(array('leaf-only'));
		$this->assertEquals( true, $request->leafOnly() );

		# Should return 'false' if X-Powerload header is not set to 'leaf-only'
		$header->andReturn(array('true'));
		$this->assertEquals( false, $request->leafOnly() );

		# Should return 'false' if X-Powerload header is not set
		$header->andReturn(NULL);
		$this->assertEquals( false, $request->leafOnly() );

		# Should return 'false' if X-Powerload header value is malformed
		$header->andReturn(array('leafonly'));
		$this->assertEquals( false, $request->leafOnly() );
	}
}