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
$debugPath = '';

// reset
foreach(glob("$shapefilesPath/BU0599*") as $json)
{
    if ($json === '.' || $json === '..') continue;
    setAangrenzende($json, $shapefilesPath);

    echo $json . PHP_EOL;

    $i++;
}

function setAangrenzende($path, $shapefilesPath)
{
    if (endsWith($path, ".json")) {
        $content = file_get_contents($path);
        $contentPolygon = getPolygonFromJson($content, $path);

        $aangrenzende = array();

        foreach (glob("$shapefilesPath/*") as $json) {
            if ($json === '.' || $json === '..' || $json === $path || getJsonType($path) !== getJsonType($json)) continue;
            $fileContent = file_get_contents($json);
            $filePolygon = getPolygonFromJson($fileContent, $json);

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

        $myfile = fopen($path, "w+") or die("Unable to open file!");
        fwrite($myfile, $temp);
        fclose($myfile);
    } else {
        echo sprintf("%s is not a JSON file and will not be processed." . PHP_EOL, $path);
    }
}

/**
 * @param string $path
 * @return string
 */
function getJsonType($path)
{
    $id = substr($path, strrpos($path, '/') + 1);
    $id = substr($id, 0, -5);
    $type = substr($id, 0, 2);

    return $type;
}

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
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
function getPolygonFromJson($content, $path)
{
    $content = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($content));
    $json = json_decode($content);

    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            foreach ($json as $object) {
                $pol = "POLYGON((";
                for ($i = 0; $i < count($object->polygon); $i++) {
                    $pol .= $object->polygon[$i][0] . " " . $object->polygon[$i][1] . ",";
                }
                $pol = substr($pol, 0, -1);
                $pol .= "))";

                return $pol;
            }
            break;
        case JSON_ERROR_DEPTH:
            echo $path . ' - Maximum stack depth exceeded';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            echo $path . ' - Underflow or the modes mismatch';
            break;
        case JSON_ERROR_CTRL_CHAR:
            echo $path . ' - Unexpected control character found';
            break;
        case JSON_ERROR_SYNTAX:
            echo $path . ' - Syntax error, malformed JSON';
            break;
        case JSON_ERROR_UTF8:
            echo $path . ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
        default:
            echo $path . ' - Unknown error';
            break;
    }
}

/**
 * @param json $input
 * @return string
 */
function getIdFromJson($content)
{
    $content = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($content));
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

