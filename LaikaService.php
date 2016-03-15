<?php
require 'vendor/autoload.php';

/**
 * Class LaikaService
 **/

class LaikaService
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
     * Constructor function for LaikaService.
     *
     * @param string $environmentName environment in which the code is being executed.
     * @param string $url             url for the API server.
     */
    public function __construct($environmentName, $url)
    {
        $this->client          = new GuzzleHttp\Client();
        $this->environmentName = $environmentName;
        $this->url             = $url;
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
        $requestResult = $this->httpRequest('GET', 'api/features');
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
     * @param  string request type.
     * @param  string API endpoint.
     * @return array|boolean returns an array with the information decoded from the json. If problems occurred, returns false.
     */
    protected function httpRequest($type, $endpoint)
    {
        $res = $this->client->request($type, $this->url . $endpoint);

        if ($res->getStatusCode() === 200) {
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
        if (isset($this->features[$featureName])) {
            return $this->features[$featureName]['status'][$this->environmentName];
        }
        trigger_error("Feature " . $featureName . " not defined", E_USER_WARNING);
        return false;
    }
}
