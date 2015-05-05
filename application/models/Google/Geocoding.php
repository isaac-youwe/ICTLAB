<?php
/**
 * Author Isaac de Cuba <isaacjdecuba@gmail.com>
 */

use Guzzle\Http\Client;

class Application_Model_Google_Geocoding
{
    private $_guzzleClient;
    public $baseUrl = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=';

    /**
     * Get the Geographic location of the search value
     *
     * @param $search
     * @return bool
     */
    public function getLocation($search)
    {
        $url = $this->baseUrl . $search;
        $request = $this->getGuzzleClient()->createRequest('GET', $url);
        $response = json_decode($request->send()->getBody(), true);

        if ($response['status'] = 'OK') {
            return $response['results'][0]['geometry']['location'];
        } else {
            return false;
        }
    }

    /**
     * @return Client
     */
    private function getGuzzleClient()
    {
        if (is_null($this->_guzzleClient)) {
            return $this->_guzzleClient = new Client();
        }
        return $this->_guzzleClient;
    }
}