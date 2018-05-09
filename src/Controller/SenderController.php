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
        curl_setopt($ch, CURLOPT_URL, 'http://XXX/iot/sender/recieve?id=44');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        $data = json_decode($response);
        return new Response($data['tekst']);
    }

    public function sendAmqp()
    {
        $start = microtime(true);

        $connection = new AMQPStreamConnection('XXX', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('iot', false, false, false, false);

        $i = 0;
        $requests = 11000;
        while($i < $requests){
            $data = 'mojaSuperwartosc ' . rand(1000, 9999);
            $msg = new AMQPMessage(json_encode($data));
            $channel->basic_publish($msg, '', 'iot');

            $i++;
        }

        $channel->close();
        $connection->close();
        $stop = microtime(true) - $start;

        return new Response("wyslalem {$requests} requestow w czasie: {$stop}");
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