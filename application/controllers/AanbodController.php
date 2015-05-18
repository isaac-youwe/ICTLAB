<?php
/**
 * Author Isaac de Cuba <isaacjdecuba@gmail.com>
 */

class AanbodController extends Zend_Controller_Action
{
    protected $_dbConnection;

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $params = $this->getRequest()->getParams();

        $this->getCitiesInView($params['seLng'], $params['nwLng'], $params['seLat'], $params['nwLat']);
    }

    /**
     * @return Zend_Db_Adapter_Pdo_Mysql
     */
    protected function _getDtbConnection()
    {
        if (is_null($this->_dbConnection)) {
            // credentials must be set in credentials.json
            return $this->_dbConnection = new Zend_Db_Adapter_Pdo_Mysql(array(
                'host' => 'localhost',
                'username' => 'root',
                'password' => 'rootpass',
                'dbname' => 'location'
            ));
        } else {
            return $this->_dbConnection;
        }
    }

    /**
     * @param $seLng
     * @param $nwLng
     * @param $seLat
     * @param $nwLat
     * @return $this|array
     */
    public function getCitiesInView($seLng, $nwLng, $seLat, $nwLat)
    {
        $sql = sprintf('SELECT * FROM Steden WHERE longitude > %s AND longitude < %s AND latitude > %s AND latitude < %s',
            $nwLng,
            $seLng,
            $seLat,
            $nwLat
        );

        try {
            $cities = $this->_getDtbConnection()->fetchAll($sql);
            return $this->_array2xml($cities);
        } catch (Exception $e) {
            echo sprintf('not able to fetch: %s', $e);
        }
        return $this;
    }

    /**
     * Function returns XML string for input associative array.
     * @param Array $array Input associative array
     * @param String $wrap Wrapping tag
     * @param Boolean $upper To set tags in uppercase
     */
    protected function _array2xml($array, $wrap='ROW0', $upper=true)
    {
        // set initial value for XML string
        $xml = '';
        // wrap XML with $wrap TAG
        if ($wrap != null) {
            $xml .= "<$wrap>\n";
        }
        // main loop
        foreach ($array as $key=>$value) {
            // set tags in uppercase if needed
            if ($upper == true) {
                $key = strtoupper($key);
            }
            // append to XML string
            $xml .= "<$key>" . htmlspecialchars(trim($value)) . "</$key>";
        }
        // close wrap TAG if needed
        if ($wrap != null) {
            $xml .= "\n</$wrap>\n";
        }
        // return prepared XML string
        return $xml;
    }
}

