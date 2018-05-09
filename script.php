<?php
/**
 * Created by PhpStorm.
 * User: momus
 * Date: 5/9/18
 * Time: 9:51 PM
 */
$start = microtime(true);

$ch = curl_init('http://momus.ovh/public/sender/recieve');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


$i = 0;
$requests = 100;
while($i < $requests){
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'mojParam' => 'mojaSuperwartosc ' . rand(1000, 9999)
    ]);
    //$response = curl_exec($ch);
    $smiec = curl_exec($ch);
    $i++;
}

curl_close($ch);
$stop = microtime(true) - $start;
echo "wyslalem {$requests} requestow w czasie: {$stop}\n";


