<?php

namespace Larchy;

class View
{
	private $laravelView;

	public function __construct( $laravelView = 'View' )
	{
		$this->laravelView = $laravelView;
	}

	public function makeLeaf( $stem, $leaf )
	{
		$laravelView = $this->laravelView;

		return $laravelView::make("$stem.$leaf")->render();
	}

	public function makeStem( $stem, $leaf )
	{
		$laravelView = $this->laravelView;

		return $laravelView::make("$stem.".LARCHY_DEFAULT_STEM_LAYOUT)->nest('leaf', "$stem.$leaf")->render();
	}

	public function make( $stem, $leaf, $data = array() )
	{
		$laravelView = $this->laravelView;

		$stem = $this->makeStem( $stem, $leaf );

		return $laravelView::make(LARCHY_DEFAULT_BASE, $data)->with('stem', $stem)->render();
	}
}