<?php

require '../vendor/autoload.php';
include_once './init.php';
require_once './topics.php';
require_once './functions.php';

function saveData($logger, $data) {
    $message = json_encode($data);
    sendKafkaMessage($logger, INTEGRACAO_SALVAR_DADOS, $message);
}

function newOrder($logger) {
    $amount = (rand() + 1) / 10;
    $order = [
        'id' => uniqid(),
        'amount' => $amount,
        'company' => [
            'name' => 'Company Inc',
            'document' => rand()
        ]
    ];

    sendKafkaMessage($logger, INTEGRACAO_REGISTRAR_PAGAMENTO, json_encode($order));
    sendKafkaMessage($logger, INTEGRACAO_EMPRESA_ATUALIZAR_DADOS, json_encode($order['company']));
}

for ($i = 0; $i < 10; $i++) {
    $data = [
        'id'  => uniqid(),
        'name' => "Name $i",
        'regitered_at' => date('Y-m-d H:i:s')
    ];
    saveData($logger, $data);
    newOrder($logger);
}
