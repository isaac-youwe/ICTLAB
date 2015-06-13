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
        $this->params = $this->view->params = $this->getRequest()->getParams();
        $this->view->assign('stad', $this->params['stad']);
        $this->view->assign('buurt', $this->params['buurt']);

        if (empty($this->params['stad'])) {
            throw new Exception('Vul een buurt of plaats in aub');
        }

        /**
         * Geocoder\Model\Address $addresses
         */
        $addresses = $this->_geocoder->geocode($this->params['stad']);

        foreach ($addresses as $address) {
            $this->view->assign('lat', $address->getLatitude());
            $this->view->assign('lng', $address->getLongitude());
        }

        $this->view->processor = $processor = new Application_Model_Processor();

        $fundaAanbod = new Application_Model_Funda_Aanbod();
        $this->view->collection = $fundaAanbod->getCollection($this->params);

        $SolrBuurtCall = $this->view->SolrBuurtCall = new Application_Model_Solr_BuurtCall();
        $aangrenzendeBuurten = $SolrBuurtCall->getAangrenzendeBuurten($this->params['buurt']);

        foreach ($aangrenzendeBuurten as $aangrenzendeBuurt) {
            echo $SolrBuurtCall->getName($aangrenzendeBuurt) . ": ";
            echo $fundaAanbod->totalObjectsCity($SolrBuurtCall->getName($aangrenzendeBuurt)) . "</br>";
            print_r($SolrBuurtCall->getPolygon($aangrenzendeBuurt)) . "</br>";
            echo "</br></br>";
        }
    }
}

