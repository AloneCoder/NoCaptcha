<?php

namespace NoCaptcha;

/**
 * Interface RequestMethod
 * @package NoCaptcha
 */
interface RequestMethod
{
    /**
     * @param RequestParameters $params
     * @return mixed
     */
    public function submit(RequestParameters $params);
}