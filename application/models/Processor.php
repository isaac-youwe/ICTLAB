<?php

class Application_Model_Processor
{
    /**
     * @param json $input
     * @return string
     */
    function getPolygonFromJson($content)
    {
        $json = json_decode($content);

        foreach ($json as $object) {
            $pol = "POLYGON((";
            for ($i = 0; $i < count($object->polygon); $i++) {
                $pol .= $object->polygon[$i][0] . " " . $object->polygon[$i][1] . ",";
            }
            $pol = substr($pol, 0, -1);
            $pol .= "))";

            return $pol;
        }
    }

    /**
     * @param array $input
     * @return string
     */
    function getPolygonFromArray($array)
    {
        if (is_array($array)) {
            $pol = "POLYGON((";

            for ($i = 0; $i < count($array); $i += 2) {
                $pol .= $array[$i] . " " . $array[$i + 1] . ",";
            }

            $pol = substr($pol, 0, -1);
            $pol .= "))";

            return $pol;
        }
        return false;
    }
}

