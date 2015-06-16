<?php

class ResultController extends Zend_Controller_Action
{
    public $params;

    public function indexAction()
    {
        $this->params = $this->view->params = $this->getRequest()->getParams();
        $this->view->assign('stad', $this->params['stad']);
        $this->view->assign('buurt', $this->params['buurt']);

        if (empty($this->params['stad'])) {
            throw new Exception('Vul een buurt of plaats in aub');
        }

        $geocoder = new Application_Model_Google_Geocoding();
        $location = $geocoder->getLocation(array($this->params['stad']));

        if (!$location) {
            throw new Exception('Geocoding error.');
        }

        $this->view->assign('lat', $location['lat']);
        $this->view->assign('lng', $location['lng']);

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
        $this->view->polygonBuurt = $polygonBuurt;
        $this->view->filteredCollection = $filteredCollection;
        
    }
}

