<?php

require_once __DIR__.'/../../../../src/greebo/essence/Request.php';

use greebo\essence\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testGet($get, $field, $expected)
    {
        $request = new Request($get, array(), array(), array(), array());
        $actual = $request->get($field);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testPost($post, $field, $expected)
    {
        $request = new Request(array(), $post, array(), array(), array());
        $actual = $request->post($field);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCookie($cookie, $field, $expected)
    {
        $request = new Request(array(), $cookie, array(), array(), array());
        $actual = $request->cookie($field);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testServer($server, $field, $expected)
    {
        $request = new Request(array(), array(), array(), array(), $server);
        $actual = $request->server($field);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testHeader($server, $field, $expected)
    {
        $request = new Request(array(), array(), array(), array(), $server);
        $actual = $request->header($field);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider attrProvider
     */
    public function testAttrIsset($key, $value, $field, $expected)
    {
        $request = new Request(array(), array(), array(), array(), array());
        if ($key)
            $request->$key = $value;
        $actual = isset($request->$field);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAttrSetGet($attrs, $field, $expected)
    {
        $request = new Request(array(), array(), array(), array(), array());
        foreach ($attrs as $key => $value)
            $request->$key = $value;
        $actual = $request->$field;
        $this->assertEquals($expected, $actual);
    }

    public function dataProvider()
    {
        return array(
            array(array(), 'foo', null),
            array(array('foo' => 'bar'), 'foo', 'bar'),
            array(array('foo' => 'bar'), 'baz', null),
        );
    }

    public function attrProvider()
    {
        return array(
            array(null, null, 'foo', false),
            array('foo', 'bar', 'foo', true),
            array('foo', 'bar', 'baz', false),
        );
    }
}