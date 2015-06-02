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
     * Get house collection using the Funda api
     *
     * @param $params
     * @return mixed
     */
    public function getCollection($params)
    {
        $request = $this->getGuzzleClient()->createRequest('GET', $this->buildUrl($params));
        $response = json_decode($request->send()->getBody());
        return $response->Objects;
    }

    /**
     * Build Funda API call url
     *
     * @param $params
     * @return mixed|string
     */
    public function buildUrl($params)
    {
        /**
         * baseurl / url / apikey / type / city / all filters / since
         */
        $this->setSince($params);
        $this->setType($params);

        // city should come right after $zo/ and then followed by buurt
        $url = '';
        $url .= $params['search'] . '/';
        foreach ($params as $key => $value) {
            if ($key === 'controller' || $key === 'action' || $key === 'module' || $key === 'search' || $key === 'buurt') continue;
            $url .= $value . '/';
        }
        return parent::getBaseUrl() . $this->_aanbodUrl . $this->getApiKey() . '/' . '?type=' . $this->getType() . '&zo=/' . $url . '&' . $this->getSince();
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