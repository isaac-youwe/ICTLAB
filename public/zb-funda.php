<?php
$buurt = $_GET["buurt"];
$ch = curl_init();
$url = "http://zb.funda.info/frontend/geo/suggest/?niveau=3&max=4&type=koop&query=$buurt";
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);

//Execute request
$response = curl_exec($ch);

//get the default response headers
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

//close connection
curl_close($ch);
flush();