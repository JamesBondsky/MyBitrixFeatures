<?php
define('DEBUG_THIS', 1);

if (DEBUG_THIS) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/me/iBlocksAndOther/iBlockWorking.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/me/iBlocksAndOther/cFileAndImages.php");
} else {
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
}

$IBLOCK_ID = 17;
$properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$IBLOCK_ID));
while ($prop_fields = $properties->GetNext())
{
    //CIBlockProperty::Add($prop_fields);
    //echo $prop_fields["ID"]." - ".$prop_fields["NAME"]."<br>";
    pre($prop_fields);
}