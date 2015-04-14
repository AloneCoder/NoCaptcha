<?php

namespace NoCaptcha\RequestMethod;


use NoCaptcha\RequestMethod;
use NoCaptcha\RequestParameters;


class SocketGet implements RequestMethod
{
    const NOCAPTCHA_HOST = 'api-nocaptcha.mail.ru';

    const SITE_VERIFY_PATH = '/check';

    const BAD_REQUEST = '{"status": "bad request", "code": 0 }';
    const BAD_RESPONSE = '{"success": "internal error", "code": 0 }';
    /**
     * @var Socket
     */
    private $socket;

    /**
     * @param Socket $socket
     */
    public function __construct(Socket $socket = null)
    {
        if (!is_null($socket)) {
            $this->socket = $socket;
        } else {
            $this->socket = new Socket();
        }
    }

    /**
     * @param RequestParameters $params
     * @return string
     */
    public function submit(RequestParameters $params)
    {
        $errno = 0;
        $errstr = '';

        if ($this->socket->fsockopen('ssl://' . self::NOCAPTCHA_HOST, 443, $errno, $errstr, 30) !== false) {
            $content = $params->toQueryString();

            $request = "GET " . self::SITE_VERIFY_PATH . " HTTP/1.1\r\n";
            $request .= "Host: " . self::NOCAPTCHA_HOST . "\r\n";
            $request .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $request .= "Content-length: " . strlen($content) . "\r\n";
            $request .= "Connection: close\r\n\r\n";
            $request .= $content . "\r\n\r\n";

            $this->socket->fwrite($request);
            $response = '';

            while (!$this->socket->feof()) {
                $response .= $this->socket->fgets(4096);
            }

            $this->socket->fclose();

            if (0 === strpos($response, 'HTTP/1.1 200 OK')) {
                $parts = preg_split("#\n\s*\n#Uis", $response);
                return $parts[1];
            }

            return self::BAD_RESPONSE;
        }

        return self::BAD_REQUEST;
    }
}