<?php

require '../vendor/autoload.php';
include_once './init.php';
require_once './topics.php';
require_once './functions.php';

$topic = INTEGRACAO;
$topics = ALL_TOPICS; // [$topic]
$index = 0;
$payload = null;

$config = setup_kafka_consumer($topics);
$consumer = new \Kafka\Consumer();
$consumer->setLogger($logger);

$consumer->start(function($topic, $part, $message) use ($logger) {

    $offset = $message['offset'];

    $logger->info("------------------------------------------------------------------------");
    $logger->info("processando menssagem $offset da partição $part do tópico $topic");
    $logger->info("------------------------------------------------------------------------");

    $function = strtolower($topic);
    $function($logger, $message['message']);
});
