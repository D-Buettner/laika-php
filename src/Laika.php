<?php

namespace Medigo;

use GuzzleHttp\Client;

/**
 * Class Laika
 **/

class Laika
{
    /**
     * GuzzleHttp Client. Used to make HTTP requests.
     *
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * Array with features. Each feature is also an array.
     *
     * @var array
     */
    protected $features = array();

    /**
     * Environment in which the code is being executed.
     *
     * @var string
     */
    protected $environmentName;

    /**
     * Basic authentication username.
     *
     * @var string
     */
    protected $username;

    /**
     * Basic authentication password.
     *
     * @var string
     */
    protected $password;

    /**
     * Constructor function for Laika
     *
     * @param string $environmentName environment in which the code is being executed.
     * @param string $url             url for the API server.
     * @param string $username        username for the basic authentication.
     * @param string $password        password for the basic authentication.
     */
    public function __construct($environmentName, $url, $username, $password)
    {
        $this->client          = new Client();
        $this->environmentName = $environmentName;
        $this->url             = $url;
        $this->username        = $username;
        $this->password        = $password;
    }

    /**
     * Setter for the feature array.
     *
     * @param array $features array with features. Each feature is also an array.
     */
    public function setFeatures($features)
    {
        $this->features = $features;
    }

    /**
     * Retrieves all the existing features through an HTTP request and adds them to the features array.
     *
     * @return boolean true if function executed as expected, false if problems occurred.
     */
    public function fetchAllFeatures()
    {
        $requestResult = $this->httpRequest('api/features');
        if ($requestResult === false) {
            return false;
        }

        foreach ($requestResult as $featureValue) {
            $this->features[$featureValue['name']] = $featureValue;
        }

        return true;
    }

    /**
     * Executes HTTP requests.
     *
     * @param  string API endpoint.
     * @return array|boolean returns an array with the information decoded from the json. If problems occurred, returns false.
     */
    protected function httpRequest($endpoint)
    {
        $res = $this->client->get($this->url . $endpoint, [
            'auth' => [$this->username, $this->password]
        ]);

        if ($res->getStatusCode() === '200') {
            $body = $res->getBody();

            $decodeResult = json_decode($body, true);
            if (is_null($decodeResult)) {
                return false;
            }

            return $decodeResult;
        }
        return false;
    }

    /**
     * Checks if feature is enabled in the current environment.
     *
     * @param  string $featureName name of the feature to check.
     * @return boolean true if feature is enabled, false if it is disabled or if problems occurred.
     */
    public function isEnabled($featureName)
    {
        if (!isset($this->features[$featureName])) {
            throw new Exception('Feature ' . $featureName . ' not defined');
        }
        return $this->features[$featureName]['status'][$this->environmentName];
    }
}
