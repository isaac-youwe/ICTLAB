<?php
use Guzzle\Http\Client;

class Application_Model_FundaApiConnector
{
    private $_apiKey;
    private $_baseUrl = 'http://partnerapi.funda.nl/feeds/Aanbod.svc/json/';
    private $_guzzleClient;
    private $_type;
    private $_since;
    private $_city;

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

    function __construct()
    {
        $string = file_get_contents(realpath(APPLICATION_PATH . '/../credentials.json'));
        $apiKey = json_decode($string, true);
        $this->setApiKey($apiKey['apikey']);
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
     * @param $params
     * @return string
     * @throws Zend_Controller_Action_Exception.
     */
    public function buildUrl($params)
    {
        $this->setCity($params);
        $this->setSince($params);
        $this->setType($params);
        return $this->_baseUrl . $this->getApiKey() . '/' . '?type=' . $this->getType() . '&zo=/' . $this->getCity() . '/0-400000' . '/&' . $this->getSince();
    }

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

    public function fundaApiCall($params)
    {

    }

    /**
     * @param $params
     * @return mixed
     */
    public function totalObjects($params)
    {
        $request = $this->getGuzzleClient()->createRequest('GET', $this->buildUrl($params));
        $response = json_decode($request->send()->getBody());
        return $response->TotaalAantalObjecten;
    }

    public function totalObjectsCity($params, $city)
    {
        $params['filters']['city'] = $city;
        $request = $this->getGuzzleClient()->createRequest('GET', $this->buildUrl($params));
        $response = json_decode($request->send()->getBody());
        return $response->TotaalAantalObjecten;
    }

    public function getCollection($params) {
        $request = $this->getGuzzleClient()->createRequest('GET', $this->buildUrl($params));
        $response = json_decode($request->send()->getBody());

        return $response->Objects;
    }
}

