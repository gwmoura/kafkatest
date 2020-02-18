<?php

require '../vendor/autoload.php';
include_once './init.php';
require_once './topics.php';
require_once './functions.php';

function saveData($data) {
    $message = json_encode($data);
    sendKafkaMessage(INTEGRACAO_SALVAR_DADOS, $message);
}

function newOrder() {
    $amount = (rand() + 1) / 10;
    $order = [
        'id' => uniqid(),
        'amount' => $amount,
        'company' => [
            'name' => 'Company Inc',
            'document' => rand()
        ]
    ];

    sendKafkaMessage(INTEGRACAO_REGISTRAR_PAGAMENTO, json_encode($order));
    sendKafkaMessage(INTEGRACAO_EMPRESA_ATUALIZAR_DADOS, json_encode($order['company']));
}

for ($i = 0; $i < 10; $i++) {
    $data = [
        'id'  => uniqid(),
        'name' => "Name $i",
        'regitered_at' => date('Y-m-d H:i:s')
    ];
    saveData($data);
    newOrder();
}


