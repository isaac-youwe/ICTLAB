<?php

/**
 * Author Isaac de Cuba <isaacjdecuba@gmail.com>
 */

use Guzzle\Http\Client;

abstract class Application_Model_FundaApiConnector
{
    private $_apiKey;
    private $_baseUrl = 'http://partnerapi.funda.nl/feeds/';
    private $_guzzleClient;
    private $_type;
    private $_since;
    private $_city;
    public $page;

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
     * Set page of the API call
     *
     * @param array $params
     * @return mixed $page
     */
    public function setPage($params)
    {
        if (!empty($params['page'])) {
            $this->page = $params['page'];
        } else {
            $this->page = '1';
        }
    }

    /**
     * Set city of the API call
     *
     * @param array $params
     * @throws Zend_Controller_Action_Exception
     */
    public function setCity($params)
    {
        if (!empty($params['search'])) {
            $this->_city = $params['search'];
        } else {
            throw new Zend_Controller_Action_Exception('Geen stad gekozen');
        }
    }

    public function getSince()
    {
        return $this->_since;
    }

    public function setSince($params)
    {
        if (!empty($params['since'])) {
            $this->_since = $params['since'];
        } else {
            $this->_since = '20090101T1200';
        }
    }

    public function getType()
    {
        return $this->_type;
    }

    public function setType($params)
    {
        if (!empty($params['type'])) {
            $this->_type = $params['type'];
        } else {
            $this->_type = 'koop';
        }
    }

    public function getBaseUrl()
    {
        return $this->_baseUrl;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->_baseUrl = $baseUrl;
    }

    public function getApiKey()
    {
        return $this->_apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
    }

    /**
     * Builds the url for the REST call
     *
     * @param array $params
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

