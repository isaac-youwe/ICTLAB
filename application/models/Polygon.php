<?php
class Application_Model_Polygon
{
    protected $_xPoints = array();
    protected $_yPoints = array();

    public function __construct($coordinates)
    {
        $this->setPoints($coordinates);
    }

    public function showPoints()
    {
        if($this->_xPoints && $this->_yPoints)
        {
            for($i = 0; $i < count($this->_xPoints); $i++)
            {
                echo $this->_xPoints[$i] + ',' + $this->_yPoints[$i];
            }
        }
    }

    /**
     * @param string $coordinates
     */
    public function setPoints($coordinates)
    {
        if (is_string($coordinates)) {
            $coordinates = explode(',', $coordinates);

            foreach($coordinates as $coordinate)
            {
                $coordinate = explode(' ', $coordinate);
                $this->_setXPoint($coordinate[0]);
                $this->_setYPoint($coordinate[1]);
            }
        }
        return;
    }

    protected function _setXPoint($x)
    {
        array_push($this->_xPoints,$this->_calcXSvg($x));
    }

    protected function _setYPoint($y)
    {
        array_push($this->_yPoints,$this->_calcYSvg($y));
    }

    /**
     * @param $longitude
     * @return mixed
     */
    private function _calcXSvg($longitude)
    {
        return ($longitude+180)*(256/360);
    }

    /**
     * @param $latitude
     * @return float
     */
    private function _calcYSvg($latitude)
    {
        return (256/2)-(256*log(tan((pi()/4)+(($latitude*pi()/180)/2)))/(2*pi()));
    }
}