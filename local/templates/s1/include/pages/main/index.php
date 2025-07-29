<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die; ?>
<main>
    <?php
    if ($_GET['ajax']) {
        $APPLICATION->RestartBuffer();

        if ($_GET['currency_date']) {
            $arFilter['DATE'] = $_GET['currency_date'];
        }
    }

    $APPLICATION->IncludeComponent(
        'custom:currency.rates',
        '',
        [
            'HL_BLOCK_ID' => 2,
            'FILTER' => $arFilter,
        ]
    );

    if ($_GET['ajax']) {
        exit();
    }
    ?>
</main>