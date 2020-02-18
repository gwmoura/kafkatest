<?php

include_once './init.php';
include_once './topics.php';

function sendKafkaMessage($topic, $message) {
    // $config = setup_kafka_producer();
    // $logger = start_logger();

    // $producer = new \Kafka\Producer(
    //     function() use ($topic, $message) {
    //         $key = time();
    //         return [
    //             [
    //                 'topic' => $topic,
    //                 'value' => $message,
    //                 'key' => $key,
    //             ],
    //         ];
    //     }
    // );

    // $producer->setLogger($logger);
    // $producer->success(function($result) {
    //     var_dump($result);
    // });
    // $producer->error(function($errorCode) {
    //     var_dump('Error: ' . $errorCode);
    // });
    // $producer->send(true);
    $producer = new \Kafka_Producer('localhost', 9092, Kafka_Encoder::COMPRESSION_NONE);
    $messages = [$message];
    $bytes = $producer->send($messages, $topic);
    return $bytes;
}

function sendSyncKafkaMessage($topic, $message) {
    $config = setup_kafka_producer();
    $producer = new \Kafka\Producer();
    $logger = start_logger();
    $key = time();

    $producer->setLogger($logger);

    $msg = [
        'topic' => $topic,
        'value' => $message,
        'key' => $key,
    ];

    print_r($msg);
    $producer->send([$msg]);
}

function integracao_salvar_dados($payload) {
    echo "--------------- integracao_salvar_dados ------------------------\n";
    print_r($payload['value']);
    echo "\n";
}

function integracao_registrar_pagamento($payload) {
    echo "--------------- integracao_registrar_pagamento ------------------------\n";
    print_r($payload['value']);
    echo "\n";
}

function integracao_empresa_atualizar_dados($payload) {
    echo "--------------- integracao_empresa_atualizar_dados ------------------------\n";
    $result = rand(0,1);
    if ($result == 0) {
        throw new Exception('Ops! Error to update data');
    }
    print_r($payload['value']);

    echo "\n";
}

function integracao_empresa_atualizar_dados_failure($payload) {
    echo "--------------- integracao_empresa_atualizar_dados_failure ------------------------\n";
    integracao_empresa_atualizar_dados($payload);
    echo "\n";
}
