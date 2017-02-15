<?php

namespace Skosm\LionDesk\Request;

use \Curl\Curl;

/**
 * Class Request
 * @package LionDesk\LionDesk
 */
class Request
{
    /**
     * Do request and return API response.
     *
     * @param string $endPoint URL of the API
     * @param string $apiKey API key
     * @param string $userKey USERKEY
     * @param array $data Data array
     * @param int $timeout Time out for request
     * @param Curl|null $curl Curl class
     *
     * @return \stdClass
     * @throws Exception
     */
    public function exec(
        string $endPoint,
        string $apiKey = '',
        string $userKey = '',
        int $timeout = 30,
        array $data = [],
        Curl $curl = null
    ) : \stdClass {
        $curl = $curl ? $curl : new Curl;
        $curl->setHeader('Content-Type', 'application/json');
        $curl->setHeader('X-LionDesk-Id', $userKey);
        $curl->setBasicAuthentication($apiKey, '');
        $curl->setTimeout($timeout);
        $curl->post($endPoint, json_encode($data));
        if ($curl->error) {
            throw new Exception($curl->errorMessage, $curl->errorCode);
        }

        return $curl->response;
    }
}
