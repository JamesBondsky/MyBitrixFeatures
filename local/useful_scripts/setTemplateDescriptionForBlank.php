<?php
define('DEBUG_THIS', 0);

if (DEBUG_THIS) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/me/iBlocksAndOther/iBlockWorking.php");
} else {
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
}

//часть 1 - всем товарам, у которых нет описания (короче 20 символов), ставит значение свойства "описание шаблонное" - true
//if (CModule::IncludeModule("iblock")) {
//    $arFilter = array('IBLOCK_ID' => 25, 'ACTIVE' => 'Y');
//    $res = CIBlockElement::GetList(array(), $arFilter, false, array(), array());
//
//    while ($ob = $res->GetNextElement()) {
//        $arFields = $ob->GetFields();
//
//        if(strlen($arFields['DETAIL_TEXT']) < 20) {
//            $href = 'https://klampi.ru/catalog/item/' . $arFields['CODE'] . "-" . $arFields['ID'] . '/';
//            pre("ID = " . $arFields['ID']. ". " . $arFields['NAME'] . ' - <a target="_blank" href="' . $href .'">' . $href .'</a>');
//
//            CIBlockElement::SetPropertyValuesEx(
//                $arFields['ID'],
//                25,
//                array("TEMPLATE_DESCRIPTION" => "465")
//            );
//        }
//    }
//}

//часть 2 - для всех товаров со значением свойства "описание шаблонное" равным true ставит какое-то шаблонное описание
if (CModule::IncludeModule("iblock")) {
    $arFilter = array('IBLOCK_ID' => 25, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array(), array());

    while ($ob = $res->GetNextElement()) {

        $arFields = $ob->GetFields();
        $arProp = $ob->GetProperties();

        if($arProp["TEMPLATE_DESCRIPTION"]["VALUE"] == "Y") {

            $href = 'https://klampi.local/catalog/item/' . $arFields['CODE'] . "-" . $arFields['ID'] . '/';
            pre("ID = " . $arFields['ID']. ". " . $arFields['NAME'] . ' - <a target="_blank" href="' . $href .'">' . $href .'</a>');


            $brandID = $arProp["BRAND"]["VALUE"];
            $brand = getElementFieldsByID($brandID);
            $text = "<p>" . $arFields["NAME"] . " – отличный выбор для вашего питомца. Мы уже много лет сотрудничаем с компанией " . $brand["NAME"] . " и абсолютно уверены в качестве продукции." .
            "</p><p>При заказе от 1 000 рублей <b>доставка бесплатна</b> для следующих городов Самарской области: Самара, Новокуйбышевск, Чапаевск, Кинель.";

            pre($text);

            $arLoadProductArray = array(
                "DETAIL_TEXT" => $text,
                "DETAIL_TEXT_TYPE" => "html"
            );

            $rsElement = new CIBlockElement;
            if ($rsElement->Update($arFields['ID'], $arLoadProductArray)) {
                echo "Элемент {$arFields["NAME"]} обновлён.\n";
            }
            //break;
        }
    }
}