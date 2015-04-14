<?php

namespace NoCaptcha;

/**
 * Class RequestParameters
 * @package NoCaptcha
 */
class RequestParameters
{
    private $secret;
    private $captchaId;
    private $captchaValue;

    /**
     * @param $secret
     * @param $captchaId
     * @param $captchaValue
     */
    public function __construct($secret, $captchaId, $captchaValue)
    {
        $this->secret = $secret;
        $this->captchaId = $captchaId;
        $this->captchaValue = $captchaValue;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $params = array(
            'private_key' => $this->secret,
            'captcha_id' => $this->captchaId,
            'captcha_value' => $this->captchaValue,
        );

        return $params;
    }

    /**
     * @return string
     */
    public function toQueryString()
    {
        return http_build_query($this->toArray(), '', '&');
    }
}