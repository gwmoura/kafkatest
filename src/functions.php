<?php

include_once './init.php';
include_once './topics.php';

function sendKafkaMessage($logger, $topic, $message) {
    $config = setup_kafka_producer();

    $producer = new \Kafka\Producer(
        function() use ($topic, $message) {
            $key = time();
            return [
                [
                    'topic' => $topic,
                    'value' => $message,
                    'key' => $key,
                ],
            ];
        }
    );

    $producer->setLogger($logger);
    $producer->success(function($result) use ($logger) {
        // var_dump($result);
        $logger->info('success', $result);
    });

    $producer->error(function($errorCode) use ($logger) {
        // var_dump('Error: ' . $errorCode);
        $logger->error('Error: ', $errorCode);
    });

    $producer->send(true);
}

function sendSyncKafkaMessage($logger, $topic, $message) {
    $config = setup_kafka_producer();

    $producer = new \Kafka\Producer();
    $producer->setLogger($logger);
    $key = time();
    $producer->send([
        [
            'topic' => $topic,
            'value' => $message,
            'key' => $key,
        ],
    ]);
}

function integracao_salvar_dados(\Monolog\Logger $logger, $payload) {
    $logger->info("--------------- integracao_salvar_dados ------------------------");
    $logger->info($payload['value']);
    sleep(1);
}

function integracao_registrar_pagamento(\Monolog\Logger $logger, $payload) {
    $logger->info("--------------- integracao_registrar_pagamento ------------------------");
    $logger->info($payload['value']);
}

function integracao_empresa_atualizar_dados(\Monolog\Logger $logger, $payload) {
    $logger->info("--------------- integracao_empresa_atualizar_dados ------------------------");
    try{
        $result = rand(0,1);
        if ($result == 0) {
            throw new Exception('Ops! Error to update data');
        }
        sleep(3);
        $logger->info($payload['value']);
    } catch (\Exception $e) {
        sendSyncKafkaMessage($logger, INTEGRACAO_EMPRESA_ATUALIZAR_DADOS_FALHA, $payload['value']);
    }
}

function integracao_empresa_atualizar_dados_falha(\Monolog\Logger $logger, $payload) {
    $logger->info("--------------- integracao_empresa_atualizar_dados_falha ------------------------");
    $logger->info($payload['value']);
}
