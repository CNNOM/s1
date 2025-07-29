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

        $hlBlockId = (int)$this->arParams['HL_BLOCK_ID'];
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

        $this->arResult = [
            'HL_BLOCK_ID' => $this->arParams['HL_BLOCK_ID'],
            'CURRENT_DATE' => $currentDate->format('d.m.Y'),
            'TODAY_RATES' => $this->getTodayRates($currentDate),
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
}