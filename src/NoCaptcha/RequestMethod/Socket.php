<?php

namespace NoCaptcha\RequestMethod;

/**
 * Class Socket
 * @package NoCaptcha\RequestMethod
 */
class Socket
{
    private $handle = null;

    /**
     * @param $hostname
     * @param int $port
     * @param int $errno
     * @param string $errstr
     * @param null $timeout
     * @return bool|null|resource
     */
    public function fsockopen($hostname, $port = -1, &$errno = 0, &$errstr = '', $timeout = null)
    {
        $this->handle = fsockopen($hostname, $port, $errno, $errstr, (is_null($timeout) ? ini_get("default_socket_timeout") : $timeout));

        if ($this->handle != false && $errno === 0 && $errstr === '') {
            return $this->handle;
        } else {
            return false;
        }
    }

    /**
     * @param $string
     * @param null $length
     * @return int
     */
    public function fwrite($string, $length = null)
    {
        return fwrite($this->handle, $string, (is_null($length) ? strlen($string) : $length));
    }

    /**
     * @param null $length
     * @return string
     */
    public function fgets($length = null)
    {
        return fgets($this->handle, $length);
    }

    /**
     * @return bool
     */
    public function feof()
    {
        return feof($this->handle);
    }

    /**
     * @return bool
     */
    public function fclose()
    {
        return fclose($this->handle);
    }
}