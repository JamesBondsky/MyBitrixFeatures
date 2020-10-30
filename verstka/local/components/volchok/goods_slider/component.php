<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;

if(!Loader::includeModule("iblock"))
{
    return;
}

$arSelect = Array();
$arFilter = Array("IBLOCK_ID" => IntVal($arParams["IBLOCK_ID"]), "ACTIVE"=>"Y", "PROPERTY_".$arParams["PROPERTY_CODE"]."_VALUE" => 'Да');
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($arFields = $res->GetNext())
{
    $arResult[] = $arFields;
}

$this->includeComponentTemplate();
?>