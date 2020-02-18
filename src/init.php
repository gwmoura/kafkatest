<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function start_logger() {
    // Create the logger
    $logger = new Logger('my_logger');
    // Now add some handlers
    $logger->pushHandler(new StreamHandler('php://stdout', Logger::WARNING));

    return $logger;
}

function setup_kafka_consumer($topics) {
    $config = \Kafka\ConsumerConfig::getInstance();
    $config->setMetadataRefreshIntervalMs(10000);
    $config->setMetadataBrokerList('localhost:9092');
    $config->setGroupId('test');
    $config->setBrokerVersion('1.0.0');
    $config->setTopics($topics);
    // $config->setOffsetReset('earliest');
    return $config;
}

function setup_kafka_producer() {
    $config = \Kafka\ProducerConfig::getInstance();
    $config->setMetadataRefreshIntervalMs(10000);
    $config->setMetadataBrokerList('localhost:9092');
    $config->setBrokerVersion('1.0.0');
    $config->setRequiredAck(1);
    $config->setIsAsyn(false);
    $config->setProduceInterval(500);

    return $config;
}
