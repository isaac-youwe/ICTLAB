<?php

class ResultController extends Zend_Controller_Action
{
    private $_filteredCollection = array();
    private $_sumLat = 0;
    private $_sumLng = 0;
    private $_total = 0;

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
        $fundaAanbod = new Application_Model_Funda_Aanbod();

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

        $polygonBuurt = $processor->getPolygonFromArray($solrBuurtCall->getPolygon($params['buurt']));
        $polygon = geoPHP::load("$polygonBuurt", "wkt");

        $totalObjectsInSearched = $fundaAanbod->totalObjectsBuurt($params['buurt']);

        $page = 1;
        if (!isset($params['p'])) {
            $params['p'] = "p$page";
        }

        while (count($this->_filteredCollection) < $totalObjectsInSearched) {
            $collection = $fundaAanbod->getCollection($params);
            $this->_filterCollection($collection, $polygon);

            // We should use thread or other solution instead of hardcoding 200 it's a work around for the demo
            if ($page == 200 || is_null($collection)) {
                break;
            }

            $page++;
            $params['p'] = "p$page";
        }

        if (!empty($this->_filteredCollection)) {
            $this->view->assign('lat', $this->_sumLat / $this->_total);
            $this->view->assign('lng', $this->_sumLng / $this->_total);
        } else {
            $this->view->assign('lat', $location['lat']);
            $this->view->assign('lng', $location['lng']);
        }

        $this->view->polygonBuurt = $polygonBuurt;
        $this->view->filteredCollection = $this->_filteredCollection;
        $this->view->totalObjectsInSearched = count($this->_filteredCollection);
    }

    /**
     * Filters the collection and push the house objects that are in the polygon to the filtered collection
     *
     * @param array $collection
     * @param polygon $polygon
     * @throws exception
     */
    private function _filterCollection($collection, $polygon)
    {
        if ($collection && $polygon) {
            foreach ($collection as $value) {
                $point1 = geoPHP::load("POINT($value->WGS84_Y $value->WGS84_X)", "wkt");
                if ($polygon->contains($point1)) {
                    array_push($this->_filteredCollection, $value);
                    $this->_sumLat += $value->WGS84_Y;
                    $this->_sumLng += $value->WGS84_X;
                    $this->_total ++;
                }
            }
        }
    }
}

