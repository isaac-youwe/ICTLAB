<?php

/**
 * Geocoder code and document available at:
 * https://github.com/geocoder-php/Geocoder
 * http://geocoder-php.org/Geocoder/
 */

use Ivory\HttpAdapter\CurlHttpAdapter;
use Geocoder\Geocoder;
use Geocoder\Model\Address;

class ResultController extends Zend_Controller_Action
{
    private $_curl;
    private $_geocoder;
    public $params;

    public function init()
    {
        /* Initialize action controller here */
        $this->_curl = new CurlHttpAdapter();
        $this->_geocoder = new \Geocoder\Provider\GoogleMaps($this->_curl);
    }

    public function indexAction()
    {
//        $this->view->assign('baseUrl', Zend_Controller_Front::getBaseUrl() . Zend_Controller_Front::getInstance()->getRequest()->getRequestUri());
        $search = $this->getRequest()->getParam('search');
        $this->view->assign('search', $search);

        /**
         * Geocoder\Model\Address $addresses
         */
        $addresses = $this->_geocoder->geocode($search);

        foreach ($addresses as $address) {
            $this->view->assign('lat', $address->getLatitude());
            $this->view->assign('lng', $address->getLongitude());
        }

        $this->params = $this->getRequest()->getParams();
        $fundaAanbod = new Application_Model_Funda_Aanbod();
        $this->view->collection = $fundaAanbod->getCollection($this->params);
    }

    public function isSelected($value)
    {
        if (in_array($value, $this->params)) {
            return true;
        }
        return false;
    }
}

