<?php

namespace luciuz\idnow;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Client;

class IdNowApi
{
    /**
     * @var string
     */
    public $companyId;

    /**
    * @var string
    */
    public $apiKey;

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
    public $idUrl;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $authToken;

    /**
     * IdNowApi constructor
     * @param $companyId
     * @param $apiKey
     * @param $url
     * @param $idUrl
     */
    public function __construct($companyId, $apiKey, $url, $version, $idUrl)
    {
        $this->companyId = $companyId;
        $this->apiKey = $apiKey;
        $this->url = $url;
        $this->version = $version;
        $this->idUrl = $idUrl;
        $this->client = new Client();
    }

    /**
     * @param string $method
     * @param array|string $params
     * @return \stdClass
     * @throws \Exception
     */
    public function do($method, $params = '')
    {
        $res = $this->client->request('POST', "{$this->url}/api/{$this->version}/{$method}", [
            RequestOptions::HEADERS => [
                'X-API-KEY' => $this->apiKey,
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
     * Create Identification
     * @param int $transactionId
     * @param array $params
     * @return \stdClass
     */
    public function create($transactionId, $params)
    {
        return $this->do("{$this->companyId}/identifications/{$transactionId}/start", $params);
    }

    /**
     * Retrieving Auth token
     * @return string
     */
    public function getAuthToken()
    {
        if (!$this->authToken) {
            $res = $this->client->request('POST', "{$this->url}/api/{$this->version}/{$this->companyId}/login", [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'application/json'
                ],
                RequestOptions::BODY => \GuzzleHttp\json_encode(['apiKey' => $this->apiKey])
            ]);
            $this->authToken = $this->handle($res)->authToken;
        }
        return $this->authToken;
    }

    /**
     * Identification URL
     * @param string $transactionId
     * @return string
     */
    public function getIdentificationUrl($transactionId)
    {
        return "{$this->idUrl}/{$this->companyId}/identifications/{$transactionId}/start";
    }
}
