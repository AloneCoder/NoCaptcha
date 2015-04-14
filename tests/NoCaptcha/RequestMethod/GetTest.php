<?php

namespace NoCaptcha\RequestMethod;

use NoCaptcha\RequestParameters;

class GetTest extends \PHPUnit_Framework_TestCase
{
    public static $assert = null;
    protected $parameters = null;
    protected $runcount = 0;

    public function setUp()
    {
        $this->parameters = new RequestParameters('secret', 'captchaId', 'captchaValue');
    }

    public function tearDown()
    {
        self::$assert = null;
    }

    public function testHTTPContextOptions()
    {
        $req = new Get();
        self::$assert = array($this, 'httpContextOptionsCallback');
        $req->submit($this->parameters);
        $this->assertEquals(1, $this->runcount, 'The assertion was ran');
    }

    public function testSSLContextOptions()
    {
        $req = new Get();
        self::$assert = array($this, 'sslContextOptionsCallback');
        $req->submit($this->parameters);
        $this->assertEquals(1, $this->runcount, 'The assertion was ran');
    }

    public function httpContextOptionsCallback(array $args)
    {
        $this->runcount++;
        $this->assertCommonOptions($args);

        $options = stream_context_get_options($args[2]);
        $this->assertArrayHasKey('http', $options);

        $this->assertArrayHasKey('method', $options['http']);
        $this->assertEquals('GET', $options['http']['method']);

        $this->assertArrayHasKey('header', $options['http']);
        $headers = array(
            'Content-type: application/x-www-form-urlencoded',
        );
        foreach ($headers as $header) {
            $this->assertContains($header, $options['http']['header']);
        }
    }

    public function sslContextOptionsCallback(array $args)
    {
        $this->runcount++;
        $this->assertCommonOptions($args);

        $options = stream_context_get_options($args[2]);
        $this->assertArrayHasKey('http', $options);
        $this->assertArrayHasKey('verify_peer', $options['http']);
        $this->assertTrue($options['http']['verify_peer']);

        $key = version_compare(PHP_VERSION, '5.6.0', '<') ? 'CN_name' : 'peer_name';

        $this->assertArrayHasKey($key, $options['http']);
        $this->assertEquals('api-nocaptcha.mail.ru', $options['http'][$key]);
    }

    protected function assertCommonOptions(array $args)
    {
        $this->assertCount(3, $args);
        $this->assertStringStartsWith('https://api-nocaptcha.mail.ru/', $args[0]);
        $this->assertFalse($args[1]);
        $this->assertTrue(is_resource($args[2]), 'The context options should be a resource');
    }
}

function file_get_contents()
{
    if (GetTest::$assert) {
        return call_user_func(GetTest::$assert, func_get_args());
    }
    return call_user_func_array('file_get_contents', func_get_args());
}
