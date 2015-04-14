<?php

namespace NoCaptcha;

class NoCaptchaTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \RuntimeException
     * @dataProvider invalidSecretProvider
     */
    public function testExceptionThrownOnInvalidSecret($invalid, $invalidId, $invalidValue)
    {
        $rc = new NoCaptcha($invalid, $invalidId, $invalidValue);
    }

    public function invalidSecretProvider()
    {
        return array(
            array('', '', ''),
            array(null, null, null),
            array(0, 0, 0),
            array(new \stdClass(), new \stdClass(), new \stdClass()),
            array(array(), array(), array()),
        );
    }

    public function testVerifyReturnsResponse()
    {
        $method = $this->getMock('\\NoCaptcha\\RequestMethod', array('submit'));
        $method->expects($this->once())
            ->method('submit')
            ->with($this->callback(function ($params) {
                return true;
            }))
            ->will($this->returnValue('{"status": "ok", "is_correct": true }'));;
        $rc = new NoCaptcha('secret', 'captchaId', 'captchaValue', $method);
        $response = $rc->verify();
        $this->assertTrue($response->isSuccess());
    }
}
