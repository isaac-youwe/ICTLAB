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

        $this->view->fundaAanbod = $fundaAanbod = new Application_Model_Funda_Aanbod();
        $this->view->collection = $collection = $fundaAanbod->getCollection($this->params);

        $SolrBuurtCall = $this->view->SolrBuurtCall = new Application_Model_Solr_BuurtCall();
        $this->view->aangrenzendeBuurten = $SolrBuurtCall->getAangrenzendeBuurten($this->params['buurt']);

        $aangrenzendeBuurten = $SolrBuurtCall->getAangrenzendeBuurten($this->params['buurt']);

        // Arrays containing "aangrenzende buurt" polygons and "aangrenzende buurt" names
        $buurtPolygons = array();
        $buurtNames = array();
        $buurtTotalObjects = array();
        $buurtStadNames = array();

        foreach ($aangrenzendeBuurten as $aangrenzendeBuurt) {
            $aangrenzendePolygon = $processor->getPolygonFromArray($SolrBuurtCall->getPolygon($aangrenzendeBuurt));
            $aangrenzendeNaam = $SolrBuurtCall->getName($aangrenzendeBuurt);

            if ($aangrenzendePolygon && $aangrenzendeNaam) {
                array_push($buurtPolygons, $aangrenzendePolygon);
                array_push($buurtNames, $aangrenzendeNaam);
                array_push($buurtTotalObjects, $fundaAanbod->totalObjectsCity($SolrBuurtCall->getName($aangrenzendeNaam)));
                array_push($buurtStadNames, $fundaAanbod->getNameStad($SolrBuurtCall->getName($aangrenzendeBuurt)));
            }
        }

        $this->view->buurtPolygons = $buurtPolygons;
        $this->view->buurtNames = $buurtNames;
        $this->view->buurtTotalObjects = $buurtTotalObjects;
        $this->view->buurtStadNames = $buurtStadNames;

        $polygonBuurt = $processor->getPolygonFromArray($SolrBuurtCall->getPolygon($this->params['buurt']));
        $polygon = geoPHP::load("$polygonBuurt", "wkt");
        $filteredCollection = array();
        foreach ($collection as $value) {
            $point1 = geoPHP::load("POINT($value->WGS84_Y $value->WGS84_X)", "wkt");
            if ($polygon->contains($point1)) {
                array_push($filteredCollection, $value);
            }
        }
        $this->view->filteredCollection = $filteredCollection;
    }
}

