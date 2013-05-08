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

		# Should return 'true' if X-Powerload header is set to 'true'
		$laravelRequest->shouldReceive('header')->once()->with('x-powerload')->andReturn(array('true'));
		$result = $request->powerload();
		$this->assertTrue($result);

		# Should return 'false' if X-Powerload header is not set
		$laravelRequest->shouldReceive('header')->once()->with('x-powerload')->andReturn(NULL);
		$result = $request->powerload();
		$this->assertFalse($result);
	}

	public function test_leafOnly_method()
	{
		extract($this->objects);

		# Should return 'true' if X-Leaf-Only header is set to 'true'
		$laravelRequest->shouldReceive('header')->once()->with('x-leaf-only')->andReturn(array('true'));
		$result = $request->leafOnly();
		$this->assertTrue($result);

		# Should return 'false' if X-Leaf-Only header is not specified
		$laravelRequest->shouldReceive('header')->once()->with('x-leaf-only')->andReturn(NULL);
		$result = $request->leafOnly();
		$this->assertFalse($result);
	}
}