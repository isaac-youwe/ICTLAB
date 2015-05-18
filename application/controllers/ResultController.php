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
    protected $_geoPHP;

    public function init()
    {
        /* Initialize action controller here */
        $this->_curl = new CurlHttpAdapter();
        $this->_geocoder = new \Geocoder\Provider\GoogleMaps($this->_curl);
    }

    public function indexAction()
    {
        $params = $this->getRequest()->getParams();
        $search = $params['search'];
        $this->view->assign('search', $search);

        /**
         * Geocoder\Model\Address $addresses
         */
        $addresses = $this->_geocoder->geocode($search);

        foreach ($addresses as $address) {
            $this->view->assign('lat', $address->getLatitude());
            $this->view->assign('lng', $address->getLongitude());
        }

        $this->view->fundaAanbod = new Application_Model_Funda_Aanbod();

        $this->_testPoly();
    }

    protected function _testPoly()
    {
        $poly1 = geoPHP::load('POLYGON((30 10,10 20,20 40,40 40,30 10))','wkt');
        $poly2 = geoPHP::load('POLYGON((35 10,10 20,15 40,45 45,35 10),(20 30, 35 35, 30 20, 20 30))','wkt');
        $combined_poly = $poly1->intersects($poly2);
//        $kml = $combined_poly->out('kml');

        echo $combined_poly;
        die();

        // Polygon WKT example
        $polygon = geoPHP::load('POLYGON((1 1,5 1,5 5,1 5,1 1),(2 2,2 3,3 3,2 2))');
        $area = $polygon->getArea();
        $centroid = $polygon->getCentroid();
        $centX = $centroid->getX();
        $centY = $centroid->getY();

        print "This polygon has an area of ".$area." and a centroid with X=".$centX." and Y=".$centY;

        // MultiPoint json example
        print "<br/>";
        $json =
            '{
               "type": "MultiPoint",
               "coordinates": [
                   [100.0, 0.0], [101.0, 1.0], [102.0, 2.0]
               ]
            }';

        $multipoint = geoPHP::load($json);
        $multipoint_points = $multipoint->getComponents();
        $first_wkt = $multipoint_points[0]->out('wkt');

        print "This multipolygon has ".$multipoint->numGeometries()." points. The first point has a wkt representation of ".$first_wkt;

    }

    public function getGeoPHP()
    {
        if(is_null($this->_geoPHP))
        {
            $this->_geoPHP = new geoPHP();
        } else {
            return $this->_geoPHP;
        }
    }
}

