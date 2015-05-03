<?php
use Guzzle\Http\Client;

abstract class Application_Model_FundaApiConnector
{
    private $_apiKey;
    private $_baseUrl = 'http://partnerapi.funda.nl/feeds/';
    private $_guzzleClient;
    private $_type;
    private $_since;
    private $_city;

    function __construct()
    {
        $string = file_get_contents(realpath(APPLICATION_PATH . '/../credentials.json'));
        $apiKey = json_decode($string, true);
        $this->setApiKey($apiKey['apikey']);
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->_city;
    }

    /**
     * @param $params
     * @throws Zend_Controller_Action_Exception
     */
    public function setCity($params)
    {
        if (!empty($params['filters']['city'])) {
            $this->_city = $params['filters']['city'];
        } else {
            throw new Zend_Controller_Action_Exception('Geen stad gekozen');
        }
    }

    /**
     * @return mixed
     */
    public function getSince()
    {
        return $this->_since;
    }

    /**
     * @param $params
     */
    public function setSince($params)
    {
        if (!empty($params['since'])) {
            $this->_since = $params['since'];
        } else {
            $this->_since = '20090101T1200';
        }
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param $params
     */
    public function setType($params)
    {
        if (!empty($params['type'])) {
            $this->_type = $params['type'];
        } else {
            $this->_type = 'koop';
        }
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->_baseUrl = $baseUrl;
    }


    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->_apiKey;
    }

    /**
     * @param mixed $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
    }

    /**
     * Builds the url for the REST call
     *
     * @param $params
     * @return mixed
     */
    abstract public function buildUrl($params);

    /**
     * @return Client
     */
    public function getGuzzleClient()
    {
        if (is_null($this->_guzzleClient)) {
            return $this->_guzzleClient = new Client();
        }
        return $this->_guzzleClient;
    }

}

