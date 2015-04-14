<?php

namespace NoCaptcha;

class RequestParametersTest extends \PHPUnit_Framework_TestCase
{

    public function provideValidData()
    {
        return array(
            array('SECRET', 'CAPTCHAID', 'CAPTCHAVALUE',
                array('private_key' => 'SECRET', 'captcha_id' => 'CAPTCHAID', 'captcha_value' => 'CAPTCHAVALUE',),
                'private_key=SECRET&captcha_id=CAPTCHAID&captcha_value=CAPTCHAVALUE'),
        );
    }

    /**
     * @dataProvider provideValidData
     */
    public function testToArray($secret, $captchaId, $captchaValue, $expectedArray, $expectedQuery)
    {
        $params = new RequestParameters($secret,$captchaId, $captchaValue);
        $this->assertEquals($params->toArray(), $expectedArray);
    }

    /**
     * @dataProvider provideValidData
     */
    public function testToQueryString($secret, $captchaId, $captchaValue, $expectedArray, $expectedQuery)
    {
        $params = new RequestParameters($secret, $captchaId, $captchaValue);
        $this->assertEquals($params->toQueryString(), $expectedQuery);
    }
}
