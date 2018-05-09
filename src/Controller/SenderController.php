<?php
/**
 * Created by PhpStorm.
 * User: momus
 * Date: 5/8/18
 * Time: 10:13 PM
 */

namespace App\Controller;

use Date;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SenderController
{

    public function send()
    {
        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, 'https://api.ryanair.com/aggregate/3/common?embedded=airports,countries,cities,regions,nearbyAirports,defaultAirport&market=en-gb');
        curl_setopt($ch, CURLOPT_URL, 'http://momus.ovh/iot/sender/recieve?id=44');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_, 1);

        $response = curl_exec($ch);

        $data = json_decode($response);
        return new Response($data['tekst']);
    }

    public function recieve(Request $request)
    {
        $data = $request->request->all();
        $data = [
            'method' => __METHOD__,
            'created' => (new \DateTime())->format('Y-m-d H:i:s')
        ] + $data;

        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('iot', false, false, false, false);
        $msg = new AMQPMessage(json_encode($data));
        $channel->basic_publish($msg, '', 'iot');
        $channel->close();
        $connection->close();

        return new JsonResponse($data, 201);
    }
}