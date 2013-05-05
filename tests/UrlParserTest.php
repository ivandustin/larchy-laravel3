<?php

use Larchy\UrlParser;

class UrlParserTest extends PHPUnit_Framework_TestCase
{

	public function tearDown()
	{
		Mockery::close();
	}

	public function test_isChild_method()
	{
		$p = new UrlParser;

		$url1 = 'http://www.domain.com/mysite/x/y';
		$base1 = 'http://www.domain.com/mysite';

		$url2 = 'http://www.domain.com/';
		$base2 = 'http://www.domain.com';

		$url3 = 'https://www.domain.com/mysite/a';
		$base3 = 'http://www.domain.com/mysite/';

		$url4 = 'http://www.different.com/mysite';
		$base4 = 'http://www.domain.com/mysite';

		$url5 = 'http://www.domain.com/a';
		$base5 = 'https://www.domain.com';

		$url6 = 'http://www.domain.com';
		$base6 = 'http://www.domain.com/mysite';

		$this->assertTrue( $p->isChild($url1, $base1) );
		$this->assertTrue( $p->isChild($url2, $base2) );
		$this->assertTrue( $p->isChild($url3, $base3) );
		$this->assertFalse( $p->isChild($url4, $base4) );
		$this->assertTrue( $p->isChild($url5, $base5) );
		$this->assertFalse( $p->isChild($url6, $base6) );
	}

	public function test_stemleaf_method()
	{
		$urlParser = new UrlParser;

		// Test fully qualified link
		$url = 'http://www.host.com/site/a/b';
		$base = 'http://www.host.com/site';

		$stemleaf = $urlParser->stemleaf( $url, $base );
		$this->assertEquals( 'a', $stemleaf['stem'] );
		$this->assertEquals( 'b', $stemleaf['leaf'] );

		// Test without leaf
		$url = 'http://www.host.com/site/a';
		$base = 'http://www.host.com/site';

		$stemleaf = $urlParser->stemleaf( $url, $base );
		$this->assertEquals( 'a', $stemleaf['stem'] );
		$this->assertEquals( 'index', $stemleaf['leaf'] );

		// Test without stem
		$url = 'http://www.host.com/site';
		$base = 'http://www.host.com/site';

		$stemleaf = $urlParser->stemleaf( $url, $base );
		$this->assertEquals( 'home', $stemleaf['stem'] );
		$this->assertEquals( 'index', $stemleaf['leaf'] );

		// Test different url than base
		$url = 'http://www.different.com/site/some/path';
		$base = 'http://www.host.com/site';

		$stemleaf = $urlParser->stemleaf( $url, $base );
		$this->assertFalse( $stemleaf );

		// Test with trailing forward slashes
		$url = 'http://www.host.com/site/a/b/';
		$base = 'http://www.host.com/site/';

		$stemleaf = $urlParser->stemleaf( $url, $base );
		$this->assertEquals( 'a', $stemleaf['stem'] );
		$this->assertEquals( 'b', $stemleaf['leaf'] );
	}

	public function test_stem_method()
	{
		$parser = Mockery::mock('Larchy\UrlParser')->makePartial();

		$url = 'http://www.domain.com/mysite/a/b';
		$base = 'http://www.domain.com/mysite';

		$parser->shouldReceive('stemleaf')->once()->with($url, $base)->andReturn( array('stem' => 'a', 'leaf' => 'b') );

		$stem = $parser->stem($url, $base);

		$this->assertEquals( 'a', $stem );
	}

	public function test_leaf_method()
	{
		$parser = Mockery::mock('Larchy\UrlParser')->makePartial();

		$url = 'http://www.domain.com/mysite/a/b';
		$base = 'http://www.domain.com/mysite';

		$parser->shouldReceive('stemleaf')->once()->with($url, $base)->andReturn( array('stem' => 'a', 'leaf' => 'b') );

		$leaf = $parser->leaf($url, $base);

		$this->assertEquals( 'b', $leaf );
	}

	public function test_clean_method()
	{
		$parser = Mockery::mock('Larchy\UrlParser')->makePartial();

		$url1 = 'http://www.domain.com/mysite/?key=value';
		$url2 = 'http://www.domain.com/';
		$url3 = 'http://www.domain.com?key=value?a=b';
		$url4 = 'http://www.domain.com/mysite/';

		$this->assertEquals( 'www.domain.com/mysite?key=value', $parser->clean($url1, 'protocol|endslash') );
		$this->assertEquals( 'http://www.domain.com/', $parser->clean($url2, 'querystring') );
		$this->assertEquals( 'http://www.domain.com', $parser->clean($url3, 'querystring') );
		$this->assertEquals( 'http://www.domain.com/mysite', $parser->clean($url4, 'endslash') );
	}

}