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
		extract( $this->objects );

		$laravelUrl->shouldReceive('full')->once()->andReturn('http://www.domain.com/mysite/');

		$result = $request->url();

		$this->assertEquals( 'http://www.domain.com/mysite/', $result );
	}

	public function test_referrer_method()
	{
		extract( $this->objects );

		$laravelRequest->shouldReceive('referrer')->once()->andReturn('http://www.referrer.com/?key=value');

		$result = $request->referrer();

		$this->assertEquals( 'http://www.referrer.com/?key=value', $result );
	}
}