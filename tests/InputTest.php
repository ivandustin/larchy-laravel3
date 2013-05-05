<?php

class InputTest extends PHPUnit_Framework_TestCase
{
	public $objects;

	public function setUp()
	{
		// Dependencies
		$laravelInput = Mockery::mock('Input');

		// Subject
		$input = Mockery::mock('Larchy\Input', array($laravelInput))->makePartial();

		$o['laravelInput'] = $laravelInput;
		$o['input'] = $input;
		$this->objects = $o;
	}

	public function tearDown()
	{
		Mockery::close();
	}

	public function test_get_method()
	{
		extract( $this->objects );

		$laravelInput->shouldReceive('get')->once()->with('input name')->andReturn('input value');

		$value = $input->get('input name');

		$this->assertEquals( 'input value', $value );
	}
}