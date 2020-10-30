<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

$hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(7)->fetch();
$entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
$entityDataClass = $entity->getDataClass();

foreach ($arResult["ITEMS"] as $cell => $arElement) {
    $arFilter = array(
//        "limit" => 2,
        "select" => array("*"),
        "order" => array("ID" => "DESC"),
        "filter" => array("UF_XML_ID" => $arElement["PROPERTIES"]["STICKERGOOD"]["VALUE"]),
    );

    $rsPropEnums = $entityDataClass::getList($arFilter);
    while ($arEnum = $rsPropEnums->fetch()){
        if($arEnum["UF_FILE"]){
            $arEnum['PREVIEW_PICTURE'] = CFile::ResizeImageGet(
                $arEnum['UF_FILE'],
                array("width" => 50, "height" => 50),
                BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                true
            );
        }
        $arResult["ITEMS"][$cell]["PROPERTIES"]["STICKERGOOD"]["PICTURE"] = $arEnum;
    }

    if (is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"])) //Product has offers
    {
        $minItemPrice = 0;
        $minItemPriceFormat = "";
        foreach ($arElement["OFFERS"] as $arOffer) {
            foreach ($arOffer["PRICES"] as $code => $arPrice) {
                if ($arPrice["CAN_ACCESS"]) {
                    if ($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]) {
                        $minOfferPrice = $arPrice["DISCOUNT_VALUE"];
                        $minOfferPriceFormat = $arPrice["PRINT_DISCOUNT_VALUE"];
                    } else {
                        $minOfferPrice = $arPrice["VALUE"];
                        $minOfferPriceFormat = $arPrice["PRINT_VALUE"];
                    }

                    if ($minItemPrice > 0 && $minOfferPrice < $minItemPrice) {
                        $minItemPrice = $minOfferPrice;
                        $minItemPriceFormat = $minOfferPriceFormat;
                    } elseif ($minItemPrice == 0) {
                        $minItemPrice = $minOfferPrice;
                        $minItemPriceFormat = $minOfferPriceFormat;
                    }
                }
            }
        }
        if ($minItemPrice > 0) {
            $arResult["ITEMS"][$cell]["MIN_OFFER_PRICE"] = $minItemPrice;
            $arResult["ITEMS"][$cell]["PRINT_MIN_OFFER_PRICE"] = $minItemPriceFormat;
        }
    }
}