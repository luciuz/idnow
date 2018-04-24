<?php

namespace luciuz\idnow;

class IdNow
{
    /**
     * @var IdNowApi
     */
    protected $api;

    /**
     * @var IdNowLogin
     */
    protected $login;

    /**
     * IdNow constructor
     * @param $companyId
     * @param $apiKey
     * @param $apiUrl
     * @param $apiVersion
     * @param $idUrl
     */
    public function __construct($companyId, $apiKey, $apiUrl, $apiVersion, $idUrl)
    {
        $this->api = new IdNowApi($companyId, $apiKey, $apiUrl, $apiVersion, $idUrl);
        $this->login = new IdNowLogin($companyId, $apiUrl, $apiVersion);
    }

    /**
     * @return IdNowApi
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @return IdNowLogin
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param int $transactionId
     * @param $params
     * @return \stdClass
     */
    public function create($transactionId, $params)
    {
        return $this->getApi()->create($transactionId, $params);
    }

    /**
     * @param int $transactionId
     * @return string
     */
    public function getUrl($transactionId)
    {
        return $this->getApi()->getIdentificationUrl($transactionId);
    }

    /**
     * @return int
     */
    public function estimatedWaitingTime()
    {
        return $this->getLogin()
        ->setAuthToken($this->getApi()->getAuthToken())
        ->estimatedWaitingTime();
    }

    /**
     * @param int $transactionId
     * @return \stdClass
     */
    public function retrieve($transactionId)
    {
        return $this->getLogin()
            ->setAuthToken($this->getApi()->getAuthToken())
            ->retrieve($transactionId);
    }

    /**
     * @param string $href
     * @param string $dir
     * @return string|false
     */
    public function download($href, $dir)
    {
        return $this->getLogin()
            ->setAuthToken($this->getApi()->getAuthToken())
            ->download($href, $dir);
    }
}
