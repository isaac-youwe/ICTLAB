<?php

/**
 * Author Isaac de Cuba
 *
 * Class Application_Model_Funda_Aanbod
 */
class Application_Model_Funda_Aanbod extends Application_Model_FundaApiConnector
{
    private $_aanbodUrl = 'Aanbod.svc/json/';

    /**
     * @return mixed
     */
    public function getAanbodUrl()
    {
        return $this->_aanbodUrl;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function getCollection($params)
    {
        $request = $this->getGuzzleClient()->createRequest('GET', $this->buildUrl($params));
        $response = json_decode($request->send()->getBody());

        return $response->Objects;
    }

    public function buildUrl($params)
    {
        /**
         * baseurl / url / apikey / type / city / all filters / since
         */
        $this->setCity($params);
        $this->setSince($params);
        $this->setType($params);

        return parent::getBaseUrl() . $this->_aanbodUrl . $this->getApiKey() . '/' . '?type=' . $this->getType() . '&zo=/' . $this->getCity() . '/0-400000' . '/p' . $this->page .'/&' . $this->getSince();
    }

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