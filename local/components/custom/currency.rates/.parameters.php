<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentParameters = [
    'PARAMETERS' => [
        'HL_BLOCK_ID' => [
            'NAME' => 'ID Highload-блока',
            'TYPE' => 'STRING',
            'DEFAULT' => '',
            'PARENT' => 'BASE',
            'REQUIRED' => 'Y'
        ],
        'CACHE_TIME' => [
            'DEFAULT' => 3600
        ]
    ]
];