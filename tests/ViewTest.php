<?php

class ViewTest extends PHPUnit_Framework_TestCase
{
	public $objects;

	public function setUp()
	{
		// Dependencies
		$laravelView = Mockery::mock('View');

		// Subject
		$view = Mockery::mock('Larchy\View', array($laravelView))->makePartial();

		$o['laravelView'] = $laravelView;
		$o['view'] = $view;
		$this->objects = $o;
	}

	public function tearDown()
	{
		Mockery::close();
	}

	public function test_makeLeaf_method()
	{
		extract( $this->objects );

		$laravelView->shouldReceive('make')->once()->with('stemname.leafname')->andReturn($laravelView);
		$laravelView->shouldReceive('render')->once()->andReturn('leaf html string');

		$leaf = $view->makeLeaf( 'stemname', 'leafname' );

		$this->assertEquals( 'leaf html string', $leaf );
	}

	public function test_makeStem_method()
	{
		extract( $this->objects );

		$laravelView->shouldReceive('make')->once()->with('stemname.layout')->andReturn($laravelView);
		$laravelView->shouldReceive('nest')->with('leaf', 'stemname.leafname')->andReturn($laravelView);
		$laravelView->shouldReceive('render')->once()->andReturn('stem with leaf html string');

		$stem = $view->makeStem( 'stemname', 'leafname' );

		$this->assertEquals( 'stem with leaf html string', $stem );
	}

	public function test_make_method()
	{
		extract( $this->objects );

		$data['title'] = 'title of the page';
		$data['meta'] = array('name' => 'content');

		$view->shouldReceive('makeStem')->once()->with('stem', 'leaf')->andReturn('stem with leaf html string');

		$laravelView->shouldReceive('make')->once()->with(LARCHY_DEFAULT_BASE, $data)->andReturn($laravelView);
		$laravelView->shouldReceive('with')->once()->with('stem', 'stem with leaf html string')->andReturn($laravelView);
		$laravelView->shouldReceive('render')->once()->andReturn('whole html document string');
		
		$whole = $view->make( 'stem', 'leaf', $data );

		$this->assertEquals( 'whole html document string', $whole );
	}
}