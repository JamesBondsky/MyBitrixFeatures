<?php

define('DEBUG_THIS', 0);

if (DEBUG_THIS) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
}


if (CModule::IncludeModule("iblock")) {
    $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => 20), false, array("ID", "NAME", "UF_BRANDTITLE"));
    while ($section = $sections->GetNext()) {
        pre($section);

        $bs = new CIBlockSection;

        $arFields = array(
            "UF_BRANDTITLE" => $section["NAME"],
        );
        $res = $bs->Update($section["ID"], $arFields);
        pre($res);
    }
}