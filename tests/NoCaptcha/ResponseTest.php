<?php

namespace NoCaptcha;

class ResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideJson
     */
    public function testFromJson($json, $success, $error)
    {
        $response = Response::parseJson($json);
        $this->assertEquals($success, $response->isSuccess());
        $this->assertEquals($error, $response->getError());
    }

    public function provideJson()
    {
        return array(
            array('{"status": "ok", "is_correct" : true }', true, array()),
            array('{"status": "bad request", "desc": "parameter \'captcha_id\' not found",  "code": 1745 }', false, array('code' => 1745, 'desc' => 'parameter \'captcha_id\' not found')),
            array('{"status": "ok", "is_correct": false }', false, array('desc' => 'Invalid input')),
            array('{"status": false}', false, array()),
            array('WTF IS JSON', false, array('desc' => 'Invalid JSON')),
        );
    }

    public function testIsSuccess()
    {
        $response = new Response(true);
        $this->assertTrue($response->isSuccess());

        $response = new Response(false);
        $this->assertFalse($response->isSuccess());
    }

    public function testGetError()
    {
        $error = array('test');
        $response = new Response(true, $error);
        $this->assertEquals($error, $response->getError());
    }
}
