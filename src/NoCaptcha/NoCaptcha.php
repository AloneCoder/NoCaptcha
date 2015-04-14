<?php

namespace NoCaptcha;

/**
 * Class NoCaptcha
 * @package NoCaptcha
 */
class NoCaptcha
{

    private $secret;
    private $captchaId;
    private $captchaValue;

    private $requestMethod;

    /**
     * @param $secret
     * @param $captchaId
     * @param $captchaValue
     * @param RequestMethod $requestMethod
     */
    public function __construct($secret, $captchaId, $captchaValue, RequestMethod $requestMethod = null)
    {
        if (empty($secret)) {
            throw new \RuntimeException('No secret provided');
        }

        if (empty($captchaId)) {
            throw new \RuntimeException('No captcha ID provided');
        }

        if (empty($captchaValue)) {
            throw new \RuntimeException('No captcha value provided');
        }

        if (!is_string($secret)) {
            throw new \RuntimeException('The provided secret must be a string');
        }

        if (!is_string($captchaId)) {
            throw new \RuntimeException('The provided captcha ID must be a string');
        }
        if (!is_string($captchaValue)) {
            throw new \RuntimeException('The provided captcha value must be a string');
        }

        $this->secret = $secret;
        $this->captchaId = $captchaId;
        $this->captchaValue = $captchaValue;

        if (!is_null($requestMethod)) {
            $this->requestMethod = $requestMethod;
        } else {
            $this->requestMethod = new RequestMethod\Get;
        }
    }

    /**
     * @return Response
     */
    public function verify()
    {
        $params = new RequestParameters($this->secret, $this->captchaId, $this->captchaValue);
        $rawResponse = $this->requestMethod->submit($params);
        return Response::parseJson($rawResponse);
    }
}