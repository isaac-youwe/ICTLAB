<?php

use Guzzle\Http\Client;

class Application_Model_Google_Geocoding
{
    private $_guzzleClient;
    private static $_baseUrl = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=';

    /**
     * @param $search
     * @return bool
     */
    static public function getLocation($search)
    {
        $url = self::$_baseUrl . urlencode($search);
        $request = self::_getGuzzleClient()->createRequest('GET', $url);
        $response = json_decode($request->send()->getBody());

        if ($response['status'] = 'OK') {
            return $response->geometry->location;
        } else {
            return false;
        }
    }

    /**
     * @return Client
     */
    private function _getGuzzleClient()
    {
        if (is_null($this->_guzzleClient)) {
            return $this->_guzzleClient = new Client();
        }
        return $this->_guzzleClient;
    }
}

