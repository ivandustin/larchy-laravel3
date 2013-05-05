<?php

class SiteTest extends PHPUnit_Framework_TestCase
{
	public $objects;

	public function setUp()
	{
		// Dependencies
		$laravelUrl = Mockery::mock('URL');

		// Subject
		$site = Mockery::mock('Larchy\Site', array($laravelUrl))->makePartial();

		$o['laravelUrl'] = $laravelUrl;
		$o['site'] = $site;
		$this->objects = $o;
	}

	public function tearDown()
	{
		Mockery::close();
	}

	public function test_base_method()
	{
		extract( $this->objects );

		$laravelUrl->shouldReceive('base')->once()->andReturn('http://www.mysite.com');

		$result = $site->base();

		$this->assertEquals( 'http://www.mysite.com', $result );
	}
}