<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

CModule::IncludeModule("iblock");

class SiteSettings
{

    public static function getProperty($value)
    {
        $arProps = array();
        $res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 6, "ACTIVE" => "Y", "CODE" => "settings"), false, array(), array("PROPERTY_*"));
        while ($ob = $res->GetNextElement()) {
            $arProps = $ob->GetProperties();
        }
        foreach ($arProps as $prop) {
            if ($value == $prop["CODE"]) {
                return $prop["VALUE"];
            }
        }
    }
}