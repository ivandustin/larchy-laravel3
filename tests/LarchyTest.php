<?php

use Mockery as m;

class LarchyTest extends PHPUnit_Framework_TestCase
{
	public $objects;

	public function setUp()
	{
		// Dependencies
		$responseBuilder = m::mock('ResponseBuilder');

		// Subject
		$larchy = m::mock('Larchy\Larchy', array($responseBuilder))->makePartial();

		$o['responseBuilder'] = $responseBuilder;
		$o['larchy'] = $larchy;
		$this->objects = $o;
	}

	public function tearDown()
	{
		m::close();
	}

	public function test_make_method()
	{
		extract( $this->objects );

		$data = array(
			'title' => 'page title',
			'meta' => array(
				'name' => 'content'
			)
		);
		$statusCode = 400;
		$headers = array(
			'field name' => 'value'
		);

		$responseBuilder->shouldReceive('make')->once()->with($data, $statusCode, $headers)->andReturn('responseObject');

		$result = $larchy->make( $data, $statusCode, $headers );
		
		$this->assertEquals( 'responseObject', $result );
	}

	public function test_make_method_given_a_string_on_data_parameter()
	{
		extract( $this->objects );

		$data = array(
			'title' => 'hello world'
		);
		$responseBuilder->shouldReceive('make')->once()->with($data, 200, array())->andReturn('responseObject');

		$result = $larchy->make( 'hello world' );
		
		$this->assertEquals( 'responseObject', $result );
	}
}