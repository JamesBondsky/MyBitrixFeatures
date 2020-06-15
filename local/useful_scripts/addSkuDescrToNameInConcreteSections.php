<?php

//скрипт устанавливает торговым предложениям имя вида: <название основного товара>, <значение указанного свойства>
//также есть проверка на принадлежность текущего товара необходимому спику разделов

define('DEBUG_THIS', 0);

if (DEBUG_THIS) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
}

define('NEEDED_SECTIONS', array(5124, 5125, 5126, 5133, 5134, 5135));
define('PROPNAME_TO_ADD_IN_TITLE', 'WEIGHT_KG');
$ii = 0;

if (CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog")) {
    $arInfo = CCatalogSKU::GetInfoByProductIBlock(25);

    $rsElements = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 25));
    $rsElement = new CIBlockElement;

    while ($arElement = $rsElements->Fetch()) {
        if (in_array($arElement['IBLOCK_SECTION_ID'], NEEDED_SECTIONS)) {
            $ii++;
            $rsOffers = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $arInfo['IBLOCK_ID'], 'PROPERTY_CML2_LINK' => $arElement['ID']));
            while ($arOffer = $rsOffers->GetNextElement()) {
                $arFieldsOffer = $arOffer->GetFields();;
                $arPropOffer = $arOffer->GetProperties();

                $newName = $arElement['NAME'] . ', ' . $arPropOffer[PROPNAME_TO_ADD_IN_TITLE]['VALUE'];
                pre($newName);

                $arLoadProductArray = array(
                    "NAME" => $newName
                );

                if ($rsElement->Update($arFieldsOffer['ID'], $arLoadProductArray)) {
                    echo "Элемент {$arFieldsOffer["NAME"]} обновлён.\n";
                }
            }
            //$href = 'https://klampi.ru/catalog/item/' . $arElement['CODE'] . "-" . $arElement['ID'] . '/';
            //pre($ii . ". ID = " . $arElement['ID'] . ". " . $arElement['NAME'] . ' - <a target="_blank" href="' . $href . '">' . $href . '</a>');
            //break;
        }
    }
}