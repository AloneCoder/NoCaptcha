<?php

namespace NoCaptcha\RequestMethod;


use NoCaptcha\RequestMethod;
use NoCaptcha\RequestParameters;

class Get implements RequestMethod
{

    const SITE_VERIFY_URL = 'https://api-nocaptcha.mail.ru/check?';

    /**
     * @param RequestParameters $params
     * @return mixed|string
     */
    public function submit(RequestParameters $params)
    {
        /**
         * PHP 5.6.0 changed the way you specify the peer name for SSL context options.
         * Using "CN_name" will still work, but it will raise deprecated errors.
         */
        $peer_key = version_compare(PHP_VERSION, '5.6.0', '<') ? 'CN_name' : 'peer_name';
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'GET',
                'verify_peer' => true,
                $peer_key => 'api-nocaptcha.mail.ru',
            ),
        );
        $context = stream_context_create($options);
        return file_get_contents(self::SITE_VERIFY_URL . $params->toQueryString(), false, $context);
    }
}