<?php

class ResultController extends Zend_Controller_Action
{
    private $_filteredCollection;

    public function indexAction()
    {
        $params = $this->view->params = $this->getRequest()->getParams();
        $this->view->assign('stad', $params['stad']);
        $this->view->assign('buurt', $params['buurt']);

        if (empty($params['stad'])) {
            throw new Exception('Vul een buurt of plaats in aub');
        }

        $geocoder = new Application_Model_Google_Geocoding();
        $location = $geocoder->getLocation(array($params['stad']));

        if (!$location) {
            throw new Exception('Stad niet gevonden. (Geocode)');
        }

        $fundaAanbod = new Application_Model_Funda_Aanbod();
        $collection = $fundaAanbod->getCollection($params);

        $solrBuurtCall = new Application_Model_Solr_BuurtCall();
        $aangrenzendeBuurten = $solrBuurtCall->getAangrenzendeBuurten($params['buurt']);

        /**
         * Arrays containing the "aangrenzende buurt" data
         */
        $buurtPolygons = array();
        $buurtNames = array();
        $buurtTotalObjects = array();
        $buurtStadNames = array();

        $processor = new Application_Model_Processor();

        foreach ($aangrenzendeBuurten as $aangrenzendeBuurt) {
            $aangrenzendePolygon = $processor->getPolygonFromArray($solrBuurtCall->getPolygon($aangrenzendeBuurt));
            $aangrenzendeNaam = $solrBuurtCall->getName($aangrenzendeBuurt);

            if ($aangrenzendePolygon && $aangrenzendeNaam) {
                array_push($buurtPolygons, $aangrenzendePolygon);
                array_push($buurtNames, $aangrenzendeNaam);
                array_push($buurtTotalObjects, $fundaAanbod->totalObjectsBuurt($aangrenzendeNaam));
                array_push($buurtStadNames, $fundaAanbod->getNameStad($aangrenzendeNaam));
            }
        }

        $this->view->buurtPolygons = $buurtPolygons;
        $this->view->buurtNames = $buurtNames;
        $this->view->buurtTotalObjects = $buurtTotalObjects;
        $this->view->buurtStadNames = $buurtStadNames;

        // variables to calculate the maps center point
        $sumLat = 0;
        $total = 0;
        $sumLng = 0;

        $polygonBuurt = $processor->getPolygonFromArray($solrBuurtCall->getPolygon($params['buurt']));
        $polygon = geoPHP::load("$polygonBuurt", "wkt");
        $this->_filteredCollection = array();
        foreach ($this->_filteredCollection as $value) {
            $point1 = geoPHP::load("POINT($value->WGS84_Y $value->WGS84_X)", "wkt");
            if ($polygon->contains($point1)) {
                array_push($this->_filteredCollection, $value);
                $sumLat += $value->WGS84_Y;
                $sumLng += $value->WGS84_X;
                $total++;
            }
        }

        // change 25 to ZB call
        $totalObjectsInSearched = $fundaAanbod->totalObjectsBuurt($params['buurt']);
        echo $totalObjectsInSearched;
        if (is_null($params['p'])) {
            $params['p'] = 1;
        }
        while (count($this->_filteredCollection) < $totalObjectsInSearched) {
            $fundaAanbod->getCollection($params);
            $params['p']++;

            // funda aanbod getCollection
            $totalObjectsInSearched -= 25;
        }

        if (!empty($this->_filteredCollection)) {
            $this->view->assign('lat', $sumLat / $total);
            $this->view->assign('lng', $sumLng / $total);
        } else {
            $this->view->assign('lat', $location['lat']);
            $this->view->assign('lng', $location['lng']);
        }

        $this->view->polygonBuurt = $polygonBuurt;
        $this->view->filteredCollection = $this->_filteredCollection;
    }

    /**
     * @param $collection
     * @param $polygon
     * @throws exception
     */
    private function _filterCollection($collection, $polygon)
    {
        if ($collection) {
            foreach ($collection as $value) {
                $point1 = geoPHP::load("POINT($value->WGS84_Y $value->WGS84_X)", "wkt");
                if ($polygon->contains($point1)) {
                    array_push($this->_filteredCollection, $value);
                }
            }
        }
    }
}

