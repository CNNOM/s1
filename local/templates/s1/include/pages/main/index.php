<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die; ?>
<main>
    <?php
    $APPLICATION->IncludeComponent(
        'custom:currency.rates',
        '',
        [
            'HL_BLOCK_ID' => 2, 
        ]
    );
    ?>
</main>