<?php
define('DEBUG_THIS', 1);

if (DEBUG_THIS) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
}

if (CModule::IncludeModule("iblock")) {
    $rsElements = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 25));

    while ($arElement = $rsElements->Fetch()) {
//       if(strlen($arElement['DETAIL_TEXT']) < 20) {
//           $href = 'https://klampi.ru/catalog/item/' . $arElement['CODE'] . "-" . $arElement['ID'] . '/';
//           pre("ID = " . $arElement['ID']. ". " . $arElement['NAME'] . ' - <a target="_blank" href="' . $href .'">' . $href .'</a>');
//       }
        if (!$arElement['PREVIEW_PICTURE']) {
            $href = 'https://klampi.ru/catalog/item/' . $arElement['CODE'] . "-" . $arElement['ID'] . '/';
            pre("ID = " . $arElement['ID']. ". " . $arElement['NAME'] . ' - <a target="_blank" href="' . $href .'">' . $href .'</a>');
        }
    }
}