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
     * @param string $environmentName Environment in which the code is being executed.
     * @param string $url URL for the API server.
     * @param string $username Username for the basic authentication.
     * @param string $password Password for the basic authentication.
     * @param array $features Optional array with features indexed by name. If the array is not
     *                        provided the features will be fetched from the client.
     */
    public function __construct($environmentName, $url, $username, $password, $features = null)
    {
        $this->client          = new Client();
        $this->environmentName = $environmentName;
        $this->url             = $url;
        $this->username        = $username;
        $this->password        = $password;
        $this->features        = $features;
    }

    /**
     * Get all the features.
     *
     * @return array $features array with features indexed by name.
     */
    public function getFeatures()
    {
        if (!$this->features) $this->preloadFeatures();
        return $this->features;
    }

    /**
     * Retrieves all the existing features through an HTTP request and adds them to the features array.
     *
     * @return boolean True if function executed as expected, false if problems occurred.
     */
    protected function preloadFeatures()
    {
        $features = $this->get('api/features');

        $this->features = array();
        foreach ($features as $feature) {
            $this->features[$feature['name']] = $feature;
        }
    }

    /**
     * Performs an HTTP Get requests, returning the body parsed as JSON.
     *
     * @param  string API endpoint.
     * @return array|boolean Returns an array with the information decoded from the json. If problems
     *                       occurred, returns false.
     */
    protected function get($endpoint)
    {
        $res = $this->client->get($this->url . $endpoint, [
            'auth' => [$this->username, $this->password]
        ]);

        $payload = json_decode($res->getBody(), true);
        if (is_null($payload)) {
            throw new Exception('Failed to decode JSON');
        }

        return $payload;
    }

    /**
     * Checks if feature is enabled in the current environment.
     *
     * @param  string $featureName name of the feature to check.
     * @return boolean True if feature is enabled, false if it is disabled or if problems occurred.
     */
    public function isEnabled($featureName)
    {
        if (!$this->features) $this->preloadFeatures();

        if (!isset($this->features[$featureName])) {
            return false;
        }

        return $this->features[$featureName]['status'][$this->environmentName];
    }
}
