<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die;
?>
<div class="inner">
    <section class="section">
        <div class="currency-exchange">
            <div class="currency-exchange__content">
                <div class="currency-exchange__heading">
                    <h2 class="ui-h2 currency-exchange__ui-h2">Курсы обмена валют
                    </h2>
                    <div class="currency-exchange__date">
                        <div class="ui-p2">на <?= $arResult['CURRENT_DATE'] ?>
                        </div>
                    </div>
                </div>
                <div class="currency-exchange__table">
                    <div class="currency-exchange__line header">
                        <div class="ui-p4">Валюта</div>
                        <div class="ui-p4">Покупка</div>
                        <div class="ui-p4">Продажа</div>
                    </div>
                    <?php foreach ($arResult['TODAY_RATES'] as $item): ?>
                        <div class="currency-exchange__line">
                            <div class="ui-p1"><?= $item['UF_CURRENCY'] ?><span>/RUB</span></div>
                            <div class="ui-p1"><?= $item['UF_BUY'] ?>
                            </div>
                            <div class="ui-p1"><?= $item['UF_SALE'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="currency-exchange__image">
                <img src="<?= SITE_TEMPLATE_PATH ?>/img/main/currency-exchange_img1.png" alt="Курсы обмена валют" />
            </div>
        </div>
    </section>
</div>

<form method="get" action="" class="date-selector" data-from>
    <input data-input type="date" id="currency-datepicker" name="currency_date"
        value="<?= $arResult['CURRENT_DATE'] ?>" class="datepicker-input">
    <button type="submit" class="datepicker-submit">Показать</button>
</form>