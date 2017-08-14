<?php

namespace Sneek\Tests\Http\Middleware;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Sneek\Http\Middleware\XSSProtection;

class XSSProtectionTest extends TestCase
{
    /** @var XSSProtection */
    protected $SUT;

    protected function setUp()
    {
        $this->SUT = new XSSProtection();
    }

    /** @test */
    function it_will_continue_passing_the_request()
    {
        $expected = Request::create('/foo');

        $actual = $this->SUT->handle($expected, function ($request) {
            return $request;
        });

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    function test_it_will_strip_tags_from_the_request()
    {
        $request = Request::create('/foo', 'POST', ['foo' => '<p>Hello</p>', 'bar' => '<p>World</p>']);

        $actual = $this->SUT->handle($request, function ($request) {
            return $request;
        });

        $this->assertEquals('Hello', $actual['foo']);
        $this->assertEquals('World', $actual['bar']);
    }

    /** @test */
    function it_will_strip_tags_recursively()
    {
        $request = Request::create('/foo', 'POST', ['nested' => ['foo' => '<p>Hello</p>', 'bar' => '<p>World</p>']]);

        $actual = $this->SUT->handle($request, function ($request) {
            return $request;
        });

        $this->assertEquals('Hello', $actual['nested']['foo']);
        $this->assertEquals('World', $actual['nested']['bar']);
    }

    /** @test */
    function it_will_encode_special_entities()
    {
        $request = Request::create('/foo', 'POST', ['nested' => ['foo' => '"\'Four > Three & Four < Five\'"']]);

        $actual = $this->SUT->handle($request, function ($request) {
            return $request;
        });

        $this->assertEquals('&quot;&#039;Four &gt; Three &amp; Four &lt; Five&#039;&quot;', $actual['nested']['foo']);
    }
}