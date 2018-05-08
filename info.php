<?php

//phpinfo();

$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, 'https://api.ryanair.com/aggregate/3/common?embedded=airports,countries,cities,regions,nearbyAirports,defaultAirport&market=en-gb');
curl_setopt($ch, CURLOPT_URL, 'http://iot.local/public/sender/recieve');
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_, 1);

$response = curl_exec($ch);

$data = json_decode($response);

var_dump($data);