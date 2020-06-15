<?php
define('DEBUG_THIS', 0);

if (DEBUG_THIS) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
}

if (CModule::IncludeModule("iblock")) {
    $rsElements = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 25));

    $rsElement = new CIBlockElement;

    $iHeight = 200;
    $iWidth = 200;

    while ($arElement = $rsElements->Fetch()) {
        $picture = false;
        if ($arElement["DETAIL_PICTURE"] != "") {
            $picture = $arElement["DETAIL_PICTURE"];
        } else {
            if ($arElement["PREVIEW_PICTURE"] != "") {
                $picture = $arElement["PREVIEW_PICTURE"];
            }
        }

        if ($picture) {
            $arPreview = CFile::ResizeImageGet($picture, array('width' => $iWidth, 'height' => $iHeight), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false, array(), false, 100);
            pre($arPreview);
            $arLoadProductArray = array(
                "PREVIEW_PICTURE" => CFile::MakeFileArray($arPreview["src"]),
            );
            if ($rsElement->Update($arElement["ID"], $arLoadProductArray)) {
                echo "Элемент {$arElement["NAME"]} обновлён.\n";
            }
        }
    }
}