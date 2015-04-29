<?php
use Guzzle\Http\Client;

class Application_Model_FundaApiConnector
{
    private $_apiKey;
    private $_baseUrl = 'http://partnerapi.funda.nl/feeds/Aanbod.svc/json/';
    private $_guzzleClient;

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
     */
    public function buildUrl($params)
    {
        $type = $params['type'];
        $city = $params['filters']['city'];
        $since = $params['since'];
        return $this->_baseUrl . $this->getApiKey() . '/' . '?type=' . $type . '&zo=/' . $city . '/0-400000' . '/&' . $since;
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

}

