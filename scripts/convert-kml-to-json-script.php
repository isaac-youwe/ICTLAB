<?php

$time_start = microtime(true);
echo 'script in progress.......' . PHP_EOL;

$rootPath = realpath(__DIR__ . '/..');
$shapefilesPath = $rootPath . '/shapefiles';
if (!file_exists("$shapefilesPath")) {
    mkdir("$shapefilesPath");
    chmod("$shapefilesPath", 0777);
}

CONST ID = 'id';
CONST TYPE = 'type';
CONST NAME = 'name';
CONST GEM_CODE = 'gemCode';
CONST WIJK_CODE = 'wijkCode';
CONST BUURT_CODE = 'buurtCode';

$path = '../data/';

$i = 0;

foreach(glob("$path*/*") as $kml)
{
    if ($kml === '.' || $kml === '..') continue;
//    echo "Filename: " . $kml . PHP_EOL;
    processFiles($kml);
    $i++;
}

/**
 * Read kml file, process and create a new json file
 *
 * @param string $path
 */
function processFiles($path)
{
    if (endsWith($path, ".kml")) {
        // content is xml
        $content = file_get_contents($path);

        // read xml file
        $xml = simplexml_load_string($content);

        $id = filter($xml->Document->name, ID);

        // json mapping
        $stringToJson = array(
            "id" => $id,
            "name" => filter($xml->Document->name, NAME),
            "geoType" => filter($xml->Document->name, TYPE),
            "gemeentecode" => filter($xml->Document->name, GEM_CODE),
            "wijkcode" => filter($xml->Document->name, WIJK_CODE),
            "buurtcode" => filter($xml->Document->name, BUURT_CODE),
            "polygon" => filterCoordinates($xml->Document->Placemark->Polygon->outerBoundaryIs->LinearRing->coordinates));

        // convert string to json
        $json = json_encode($stringToJson);
//        echo $json . PHP_EOL;

        // create .json file into shapefiles folder
        createFile($id, $json);
        } else {
            echo sprintf("%s is not a KML file and will not be processed." . PHP_EOL, $path);
    }
}

/**
 * Create new json file
 *
 * @param string $name
 * @param string $content
 */
function createFile($name, $content)
{
    $jsonFile = fopen("../shapefiles/$name.json", "w") or die("Unable to open file!");

    fwrite($jsonFile, "[$content]");
    fclose($jsonFile);
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

/**
 * Convert the string coordinates from the xml file into json objects
 *
 * @param string $coordinates
 * @return array $polylines
 */
function filterCoordinates($coordinates)
{
    $polylines = array();

    $coordinates = str_replace(array("\n", "\r"), '', $coordinates);
    $coordinates = str_replace("             ", "", $coordinates);
    $coordinates = str_replace("            ", "", $coordinates);
    $coordinates = str_replace(",0.000000", ",", $coordinates);

    $coordinatesArray = explode(',', $coordinates);
    $coordinatesArray = array_slice($coordinatesArray, 0, count($coordinatesArray) - 1);

    for ($i = 0; $i < count($coordinatesArray); $i += 2) {
        $obj = array(
            floatval($coordinatesArray[$i + 1]), floatval($coordinatesArray[$i])
        );
        array_push($polylines, $obj);
    }

    return $polylines;
}

/**
 * @param string $documentName
 * @param CONSTANT $type
 * @return string
 */
function filter($documentName, $type)
{
    $string = preg_split('/\s+/', $documentName);

    switch ($type) {
        case ID:
            return $string[0];
            break;

        case TYPE:
            return substr($documentName, 0, 2);
            break;

        case NAME:
            // the string variable is split by space. Create a new string with variable after the first space.
            $count = count($string);
            if ($count > 2) {
                $line = null;
                for ($i = 1; $i < $count; $i++) {
                    $line .= $string[$i];
                    if ($i != $count - 1) {
                        $line .= ' ';
                    }
                }
                return $line;
            }
            return $string[1];

        case GEM_CODE:
            $string  = $string[0];
            return substr($string, 2, 4);

        case WIJK_CODE:
            $string  = $string[0];
            if (strlen($string) > 6) {
                return substr($string, 6, 2);
            } else {
                return '';
            }

        case BUURT_CODE:
            $string  = $string[0];
            if (strlen($string) > 8) {
                return substr($string, 8, 2);
            } else {
                return '';
            }

        default:
            echo 'Filter type is not correct!' . PHP_EOL;
            return '';
    }
}

echo PHP_EOL;
echo 'script done' . PHP_EOL;

$time_end = microtime(true);

$execution_time = $time_end - $time_start;

echo $i . ' files were processed in ' . "$execution_time seconds" . PHP_EOL;