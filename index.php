<?php

require 'vendor/autoload.php';
/**
 * Created by PhpStorm.
 * User: momus
 * Date: 5/7/18
 * Time: 9:34 PM
 */
try{
    $m = new MongoDB\Client('mongodb://mongodb');
    $db = $m->mojabaza;
    $result = $db->createCollection('nowakolekcja');
}catch(Exception $exception){
    echo 'lipa' . $exception->getMessage();
}

var_dump($result);

die('super!:)');