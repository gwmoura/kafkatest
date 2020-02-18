<?php

require '../vendor/autoload.php';
include_once './init.php';
require_once './topics.php';
require_once './functions.php';


$topic = INTEGRACAO;
$topics = ALL_TOPICS; // [$topic]
$index = 0;
$logger = start_logger();
$config = setup_kafka_consumer($topics);

$consumer = new \Kafka\Consumer();
$consumer->setLogger($logger);
$consumer->start(function($topic, $part, $message) {
    global $index;
    // print_r([
    //     $topic,
    //     $part,
    //     $message
    // ]);
    try{
        $function = strtolower($topic);
        $function($message['message']);
    } catch(\Exception $e) {
        $deadLetterTopic = $topic . 'DEAD_LETTER';
        $encodedMessage = $message['message']['value'];
        print_r(['$encodedMessage' => $encodedMessage]);
        if($encodedMessage) {
            sendSyncKafkaMessage($deadLetterTopic, $encodedMessage);
        }
    }

    echo $index++;
});
