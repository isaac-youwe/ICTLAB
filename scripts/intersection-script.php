<?php

$time_start = microtime(true);
echo 'script in progress.......' . PHP_EOL;

$rootPath = realpath(__DIR__ . '/..');
$shapefilesPath = $rootPath . '/shapefiles';
if (!file_exists("$shapefilesPath")) {
    echo 'script stopped.......' . PHP_EOL;
    echo 'please run php convert-kml-to-json-script.php first' . PHP_EOL;
    die();
}

require_once '/home/isaac/vhosts/ICTLAB/vendor/autoload.php';

$i = 0;

foreach(glob("$shapefilesPath/*") as $json)
{
    if ($json === '.' || $json === '..') continue;
    setAangrenzende($json, $shapefilesPath);
    $i++;
}

function setAangrenzende($path, $shapefilesPath)
{
    if (endsWith($path, ".json")) {
        // content is json
        $content = file_get_contents($path);
        $contentPolygon = getPolygonFromJson($content);


        $aangrenzende = array();

        foreach(glob("$shapefilesPath/$type*") as $json)
        {
            if ($json === '.' || $json === '..' || $json === $path || !isSameType($path, $json)) continue;
            $fileContent = file_get_contents($json);
            $filePolygon = getPolygonFromJson($fileContent);
            if (isAangrenzend($contentPolygon, $filePolygon)) {
                array_push($aangrenzende, getIdFromJson($fileContent));
            }
        }

        $temp = substr($content,0,-2);
        $temp .=',"aangrenzende":[';

        foreach ($aangrenzende as $id) {
            $temp .= '"' . $id . '",';
        }
        $temp = substr($temp,0,-1);
        $temp .= ']}]';

        echo "type: " . $type . PHP_EOL;
        echo $temp . PHP_EOL;
        die();
        $myfile = fopen($path, "w+") or die("Unable to open file!");
        fwrite($myfile, $temp);
        fclose($myfile);
    } else {
        echo sprintf("%s is not a JSON file and will not be processed." . PHP_EOL, $path);
    }
}

function isSameType($path, $checkPath)
{
    if () {
        return true;
    }
    return false;
}

/**
 * @param Polygon $poly1
 * @param Polygon $poly2
 * @return bool
 */
function isAangrenzend($polygonOne, $polygonTwo)
{
    $poly1 = geoPHP::load($polygonOne,'wkt');
    $poly2 = geoPHP::load($polygonTwo,'wkt');

    if ($poly1->intersects($poly2)) {
        return true;
    }
    return false;
}

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
 * @param json $input
 * @return string
 */
function getIdFromJson($content)
{
    $json = json_decode($content);

    foreach ($json as $object) {
        return $object->id;
    }
}

/**
 * Check if a string ends with a specific word or character
 *
 * @param $haystack
 * @param $needle
 * @return bool
 */
function endsWith($haystack, $needle)
{
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

echo PHP_EOL;
echo 'script done' . PHP_EOL;

$time_end = microtime(true);

$execution_time = $time_end - $time_start;

echo $i . ' files were processed in ' . "$execution_time seconds" . PHP_EOL;

