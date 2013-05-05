<?php

class ResponseTest extends PHPUnit_Framework_TestCase
{
	public $objects;

	public function setUp()
	{
		// Dependencies
		$laravelResponse = Mockery::mock('Response');

		// Subject
		$response = Mockery::mock('Larchy\Response', array($laravelResponse))->makePartial();

		$o['laravelResponse'] = $laravelResponse;
		$o['response'] = $response;
		$this->objects = $o;
	}

	public function tearDown()
	{
		Mockery::close();
	}

	public function test_make_method()
	{
		extract( $this->objects );

		$content = 'content string';
		$statusCode = 200;
		$headers = array('key' => 'value');

		$laravelResponse->shouldReceive('make')->once()->with($content, $statusCode, $headers)->andReturn('response object');

		$result = $response->make( $content, $statusCode, $headers );

		$this->assertEquals( 'response object', $result );
	}

	public function test_json_method()
	{
		extract( $this->objects );

		$data = array('a' => 'b');
		$statusCode = 200;
		$headers = array('key' => 'value');

		$laravelResponse->shouldReceive('json')->once()->with($data, $statusCode, $headers)->andReturn('json response object');

		$result = $response->json( $data, $statusCode, $headers );

		$this->assertEquals( 'json response object', $result );
	}
}