<?
define('DEBUG_THIS', 0);

if (DEBUG_THIS) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
}

//translitXmlForEnumFeature(366);

function translitXmlForEnumFeature($propertyID)
{
    //может понадобиться
    //if (CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog")) {
        $res = CIBlockProperty::GetPropertyEnum($propertyID);
        $ar_all_values = Array();
        while ($f = $res->Fetch()) {
            $id = $f['ID'];
            $arParams = array("replace_space" => "_", "replace_other" => "_");
            $ar_all_values[$id] = Array('VALUE' => $f['VALUE'], 'XML_ID' => Cutil::translit($f['VALUE'], "ru", $arParams));
        }

        $CIBlockProp = new CIBlockProperty;
        $CIBlockProp->UpdateEnum($propertyID, $ar_all_values);
    //}
}

function toLowerCaseForEnumFeature($propertyID)
{
    //может понадобиться
    //if (CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog")) {
    $res = CIBlockProperty::GetPropertyEnum($propertyID);
    $ar_all_values = Array();
    while ($f = $res->Fetch()) {
        $id = $f['ID'];
        $arParams = array("replace_space" => "_", "replace_other" => "_");
        $ar_all_values[$id] = Array('VALUE' => mb_strtolower($f['VALUE']), 'XML_ID' => $f['XML_ID']);
    }

    $CIBlockProp = new CIBlockProperty;
    $CIBlockProp->UpdateEnum($propertyID, $ar_all_values);
    //}
}

