<?php

use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Type\Date;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class CurrencyRatesComponent extends CBitrixComponent
{
    private const MAX_ITEMS_LIMIT = 100;
    private const CBR_API_URL = 'http://www.cbr.ru/scripts/XML_daily.asp';

    private $entityClass;

    public function executeComponent()
    {
        if (!$this->validateParameters()) {
            return;
        }

        if (!$this->initializeHighloadBlock()) {
            return;
        }

        $this->prepareResult();
        $this->includeComponentTemplate();
    }

    private function validateParameters(): bool
    {
        if (empty($this->arParams['HL_BLOCK_ID'])) {
            ShowError('Не указан ID highload-блока');
            return false;
        }
        return true;
    }

    private function initializeHighloadBlock(): bool
    {
        if (!Loader::includeModule('highloadblock')) {
            ShowError('Модуль highload-блоков не установлен');
            return false;
        }

        $hlBlockId = (int) $this->arParams['HL_BLOCK_ID'];
        $hlBlock = HighloadBlockTable::getById($hlBlockId)->fetch();

        if (!$hlBlock) {
            ShowError('Highload-блок не найден');
            return false;
        }

        $this->entityClass = HighloadBlockTable::compileEntity($hlBlock)->getDataClass();
        return true;
    }

    private function prepareResult(): void
    {
        $currentDate = new Date(date('Y-m-d'), 'Y-m-d');
        $todayRates = $this->getTodayRates($currentDate);

        $hasUSD = $this->checkCurrency($todayRates, 'USD');
        $hasEUR = $this->checkCurrency($todayRates, 'EUR');

        if (!$hasUSD || !$hasEUR) {
            $apiRates = $this->getCourseApi($currentDate, !$hasUSD, !$hasEUR);
            $todayRates = array_merge($todayRates, $apiRates);
        }
        
        $this->arResult = [
            'HL_BLOCK_ID' => $this->arParams['HL_BLOCK_ID'],
            'CURRENT_DATE' => $currentDate->format('d.m.Y'),
            'TODAY_RATES' => $todayRates,
            'ITEMS' => $this->getAllRates(),
        ];
    }

    private function getTodayRates(Date $date): array
    {
        return $this->entityClass::getList([
            'select' => ['*'],
            'filter' => ['=UF_DATE' => $date],
            'order' => ['ID' => 'ASC']
        ])->fetchAll();
    }

    private function getAllRates(): array
    {
        return $this->entityClass::getList([
            'select' => ['*'],
            'order' => ['UF_DATE' => 'DESC', 'ID' => 'ASC'],
            'limit' => self::MAX_ITEMS_LIMIT
        ])->fetchAll();
    }

    private function checkCurrency(array $rates, string $currency): bool
    {
        foreach ($rates as $rate) {
            if ($rate['UF_CURRENCY'] === $currency) {
                return true;
            }
        }
        return false;
    }

    private function getCourseApi(Date $date, bool $needUSD, bool $needEUR): array
    {
        $dateFormatted = $date->format('d/m/Y');
        $url = self::CBR_API_URL . '?date_req=' . $dateFormatted;

        $xml = simplexml_load_file($url);
        if ($xml === false) {
            return [];
        }

        $rates = [];
        foreach ($xml->Valute as $valute) {
            $code = (string) $valute->CharCode;
            if (($needUSD && $code === 'USD') || ($needEUR && $code === 'EUR')) {
                $rate = [
                    'UF_DATE' => $date,
                    'UF_CURRENCY' => (string) $valute->CharCode,
                    'UF_CURRENCY_NAME' => (string) $valute->Name,
                    'UF_BUY' => (float) $valute->Value,
                    'UF_SALE' => (float) $valute->VunitRate,
                ];

                $result = $this->entityClass::add($rate);
                if ($result->isSuccess()) {
                    $rate['ID'] = $result->getId();
                    $rates[] = $rate;
                }
            }
        }
        return $rates;
    }
}