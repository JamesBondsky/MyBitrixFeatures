<?php
define('DEBUG_THIS', 1);

if (DEBUG_THIS) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
}

$manufacturer = json_decode(file_get_contents("oc_manufacturer.json"), 1)[2]['data'];
$manufacturerDescr = json_decode(file_get_contents("oc_manufacturer_description.json"), 1)[2]['data'];

$el = new CIBlockElement();
foreach ($manufacturer as $manuf) {
    $manId = $manuf['manufacturer_id'];
    $manName = $manuf['name'];

    foreach ($manufacturerDescr as $manufDescr) {
        if ($manId == $manufDescr['manufacturer_id']) {
            $manDetail = $manufDescr['description'];
            break;
        }
    }

    $arParams = array("replace_space"=>"_", "replace_other"=>"-");
    $arFields = Array(
        "IBLOCK_ID" => 12,
        "NAME" => $manName,
        "CODE" => CUtil::translit($manName, 'en', $arParams),
        "DETAIL_TEXT" => $manDetail,
        "DETAIL_TEXT_TYPE" => 'html',
        "PROPERTY_VALUES" => array()
    );
    $i = $el->Add($arFields);
    pre($i);
}