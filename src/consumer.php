<?php

require '../vendor/autoload.php';
include_once './init.php';
require_once './topics.php';
require_once './functions.php';


$topic = INTEGRACAO;
$topics = ALL_TOPICS; // [$topic]
$index = 0;
$payload = null;
$logger = start_logger();
$config = setup_kafka_consumer($topics);

$consumer = new \Kafka\Consumer();
$consumer->setLogger($logger);
try {
    $consumer->start(function($topic, $part, $message) {
        global $index, $payload;

        print_r([
            $topic,
            $part,
            $message
        ]);
        $payload = $message['message']['value'];

        $function = strtolower($topic);
        $function($message['message']);

        echo $index++;
    });
} catch (\Exception $e) {
    $deadLetterTopic = $topic . 'DEAD_LETTER';
    print_r(['$payload' => $payload]);
    if ($payload) {
        sendKafkaMessage($deadLetterTopic, $payload);
    }
}

// $topic = INTEGRACAO;
// $partition     = 0;
// $offset        = 0;
// $maxSize       = 1000000;
// $socketTimeout = 5;
// $index = 0;

// while (true) {
//     $consumer = new Kafka_SimpleConsumer('localhost', 9092, $socketTimeout, $maxSize);
//     $fetchRequest = new Kafka_FetchRequest($topic, $partition, $offset, $maxSize);
//     $messages = $consumer->fetch($fetchRequest);
//     foreach ($messages as $msg) {
//         // echo "\nMessage: " . $msg->payload();
//         // try{
//         //     $function = strtolower($topic);
//         //     print_r($msg->payload());
//         //     $function($msg->payload());
//         // } catch(\Exception $e) {
//         //     $deadLetterTopic = $topic . 'DEAD_LETTER';
//         //     $encodedMessage = $msg->payload();
//         //     print_r(['$encodedMessage' => $encodedMessage]);
//         //     if($encodedMessage) {
//         //         sendKafkaMessage($deadLetterTopic, $encodedMessage);
//         //     }
//         // }

//         $function = strtolower($topic);
//         print_r($msg->payload());
//         $function($msg->payload());

//         echo $index++;
//     }
//     //advance the offset after consuming each MessageSet
//     $offset += $messages->validBytes();
//     unset($fetchRequest);
// }
