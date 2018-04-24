<?php

namespace luciuz\idnow;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Client;

class IdNowLogin
{
    /**
     * @var string
     */
    public $companyId;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $version;

    /**
     * @var string
     */
    public $authToken;

    /**
     * @var Client
     */
    protected $client;

    /**
     * IdNowLogin constructor
     * @param string $companyId
     * @param string $url
     */
    public function __construct($companyId, $url, $version)
    {
        $this->companyId = $companyId;
        $this->url = $url;
        $this->version = $version;
        $this->client = new Client();
    }

    /**
     * @param string $path
     * @param array|string $params
     * @param string $method
     * @return \stdClass
     * @throws \Exception
     */
    public function do($path, $params = '', $method = 'GET')
    {
        $res = $this->client->request($method, "{$this->url}/api/{$this->version}/{$path}", [
            RequestOptions::HEADERS => [
                'X-API-LOGIN-TOKEN' => $this->authToken,
                'Content-Type' => 'application/json'
            ],
            RequestOptions::BODY => \GuzzleHttp\json_encode($params, JSON_FORCE_OBJECT)
        ]);
        return $this->handle($res);
    }

    /**
     * @param Response $res
     * @return \stdClass
     * @throws \Exception
     */
    public function handle($res)
    {
        return \GuzzleHttp\json_decode($res->getBody());
    }

    /**
     * Estimated Waiting Time for identification
     * @return int
     */
    public function estimatedWaitingTime()
    {
        return $this->do("{$this->companyId}")->estimatedWaitingTime;
    }

    /**
     * Retrieve single identification result
     * @param $transactionId
     * @return \stdClass
     */
    public function retrieve($transactionId)
    {
        return $this->do("{$this->companyId}/identifications/{$transactionId}");
    }

    /**
     * @param $authToken
     * @return $this
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;
        return $this;
    }

    /**
     * @param $href
     * @param $dir
     * @return string|false
     */
    public function download($href, $dir)
    {
        $basename = pathinfo($href, PATHINFO_BASENAME);
        $path = "{$dir}/{$basename}";
        $res = $this->client->request('GET', "{$this->url}{$href}", [
            RequestOptions::SINK => "{$dir}/{$basename}",
            RequestOptions::HEADERS => [
                'X-API-LOGIN-TOKEN' => $this->authToken,
            ]
        ]);
        return $res->getStatusCode() == 200 ? $path : false;
    }
}
