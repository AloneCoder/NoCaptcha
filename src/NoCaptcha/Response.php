<?php

namespace NoCaptcha;


class Response
{
    private $success = false;

    private $error = array();

    /**
     * @param $json
     * @return Response
     */
    public static function parseJson($json)
    {
        $responseData = json_decode($json, true);

        if (!$responseData) {
            return new Response(false, array(
                'desc' => 'Invalid JSON'
            ));
        }

        if (isset($responseData['status'], $responseData['is_correct'])
            && $responseData['is_correct'] === true
            && $responseData['status'] === 'ok'
        ) {
            return new Response(true);
        }

        if (isset($responseData['status'], $responseData['is_correct'])
            && $responseData['is_correct'] === false
            && $responseData['status'] === 'ok'
        ) {
            return new Response(false, array(
                'desc' => 'Invalid input'
            ));
        }
        if (isset($responseData['code'], $responseData['desc'])) {
            return new Response(false, array(
                'code' => $responseData['code'],
                'desc' => $responseData['desc']
            ));
        }

        return new Response(false);
    }

    /**
     * @param $success
     * @param array $error
     */
    public function __construct($success, array $error = array())
    {
        $this->success = $success;
        $this->error = $error;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }
}