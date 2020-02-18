<?php

require '../vendor/autoload.php';
include_once './init.php';
require_once './topics.php';
require_once './functions.php';


// $topic = INTEGRACAO;
// $topics = ALL_TOPICS; // [$topic]
// $index = 0;
// $logger = start_logger();
// $config = setup_kafka_consumer($topics);

// $consumer = new \Kafka\Consumer();
// $consumer->setLogger($logger);
// $consumer->start(function($topic, $part, $message) {
//     global $index;
//     // print_r([
//     //     $topic,
//     //     $part,
//     //     $message
//     // ]);
//     try{
//         $function = strtolower($topic);
//         $function($message['message']);
//     } catch(\Exception $e) {
//         $deadLetterTopic = $topic . 'DEAD_LETTER';
//         $encodedMessage = $message['message']['value'];
//         print_r(['$encodedMessage' => $encodedMessage]);
//         if($encodedMessage) {
//             sendSyncKafkaMessage($deadLetterTopic, $encodedMessage);
//         }
//     }

//     echo $index++;
// });

$topic = INTEGRACAO;
$partition     = 0;
$offset        = 0;
$maxSize       = 1000000;
$socketTimeout = 5;
$index = 0;

while (true) {
    $consumer = new Kafka_SimpleConsumer('localhost', 9092, $socketTimeout, $maxSize);
    $fetchRequest = new Kafka_FetchRequest($topic, $partition, $offset, $maxSize);
    $messages = $consumer->fetch($fetchRequest);
    foreach ($messages as $msg) {
        // echo "\nMessage: " . $msg->payload();
        try{
            $function = strtolower($topic);
            print_r($msg->payload());
            $function($msg->payload());
        } catch(\Exception $e) {
            $deadLetterTopic = $topic . 'DEAD_LETTER';
            $encodedMessage = $msg->payload();
            print_r(['$encodedMessage' => $encodedMessage]);
            if($encodedMessage) {
                sendKafkaMessage($deadLetterTopic, $encodedMessage);
            }
        }

        echo $index++;
    }
    //advance the offset after consuming each MessageSet
    $offset += $messages->validBytes();
    unset($fetchRequest);
}
