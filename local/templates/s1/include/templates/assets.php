<?php

use Bitrix\Main\Page\Asset;

global $USER;
Asset::getInstance()->addString('<meta charset="UTF-8">');

Asset::getInstance()->addString('<script type="module" src="' . SITE_TEMPLATE_PATH . '/js/script.js"></script>');
Asset::getInstance()->addString('<script type="module" src="' . SITE_TEMPLATE_PATH . '/js/vendors.js"></script>');
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/style.min.css");
