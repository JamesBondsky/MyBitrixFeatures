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

$cadIdArr = array(
    '83' => 5068,
    '95' => 5078,
    '96' => 5079,
    '97' => 5080,
    '110' => 5091,
    '84' => 5069,
    '98' => 5081,
    '99' => 5082,
    '115' => 5096,
    '114' => 5095,
    '111' => 5092,
    '112' => 5093,
    '113' => 5094,
    '116' => 5097,
    '65' => 5065,
    '70' => 5072,
    '71' => 5073,
    '89' => 5099,
    '90' => 5074,
    '78' => 5098,
    '79' => 5101,
    '94' => 5076,
    '108' => 5077,
    '86' => 5071,
    '101' => 5084,
    '100' => 5083,
    '105' => 5088,
    '106' => 5089,
    '103' => 5086,
    '104' => 5087,
    '107' => 5090,
    '82' => 5067,
    '85' => 5070,
    '64' => 5064,
    '69' => 5102,
    '68' => 5066,
    '109' => 5105,
    '88' => 5106,
    '77' => 5107,
    '92' => 5104,
    '102' => 5085,
    '87' => 5103,
    '91' => 5075,
    '76' => 5100,
);


$brandsArray = array(
    '12' => 288,
    '13' => 289,
    '14' => 290,
    '15' => 292,
    '16' => 293,
    '17' => 294,
    '18' => 1324,
    '11' => 291,
);


$el = new CIBlockElement();
//
//$el->Update(
//    1318,
// array('PREVIEW_PICTURE' => CFile::MakeFileArray('/upload/medialibrary/067/0671d6ba306d771c6556aa2816269f7c.png'))
//);
//
//pre(getElementFieldsByID(1318),1);

$products = json_decode(file_get_contents("oc_product.json"), 1)[2]['data'];
$productsDescr = json_decode(file_get_contents("oc_product_description.json"), 1)[2]['data'];
$productsCategory = json_decode(file_get_contents("oc_product_to_category.json"), 1)[2]['data'];

//pre($products, 1);


$mediaLibrary = array();

$res = CFile::GetList();
while ($res_arr = $res->GetNext()) {
    if (strpos($res_arr["SUBDIR"], 'medialibrary') !== false) {
        $mediaLibrary[$res_arr['ID']]['ORIGINAL'] = $res_arr["ORIGINAL_NAME"];
        $mediaLibrary[$res_arr['ID']]['FILE'] = $res_arr["FILE_NAME"];
    }
}


$obPrice = new CPrice();

foreach ($products as $product) {
    $prodId = $product['product_id'];
    $manufacturer = $product['manufacturer_id'];
    $image = $product['image'];

    $image = getImageDataByFileName($image, $mediaLibrary);

    $price = $product['price'];

    foreach ($productsDescr as $productDescr) {
        if ($prodId == $productDescr['product_id']) {
            $prodName = $productDescr['name'];
            //$prodName = str_replace('&amp;', '&', $prodName);
            //$prodName = str_replace('&quot;', '"', $prodName);
            $prodDetail = $productDescr['description'];
            break;
        }
    }

    foreach ($productsCategory as $productCategory) {
        if ($prodId == $productCategory['product_id']) {
            $category = $productCategory['category_id'];
            break;
        }
    }

    $PROP = array();
    $PROP['BRAND'] = $brandsArray[$manufacturer];

    $arFields = Array(
        "IBLOCK_ID" => 25,
        'IBLOCK_SECTION_ID' => $cadIdArr[$category],
        "NAME" => htmlspecialchars_decode($prodName),
        "DETAIL_TEXT" => htmlspecialchars_decode($prodDetail),
        "DETAIL_TEXT_TYPE" => 'html',
        //"PREVIEW_PICTURE" => $idIm,
        "DETAIL_PICTURE" => $image,
        "PREVIEW_PICTURE" => $image,
        "PROPERTY_VALUES" => $PROP
    );

    $PRODUCT_ID = $el->Add($arFields);

    $arFieldsCatalog = array(
        "ID" => $PRODUCT_ID,
        "AVAILABLE " => "Y",
        "QUANTITY" => 9999999,
        "MEASURE" => 5

    );

    CCatalogProduct::Add($arFieldsCatalog);

    $PRICE_TYPE_ID = 1;

    $arFieldsPrice = array(
        "PRODUCT_ID" => $PRODUCT_ID,
        "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
        "PRICE" => $price,
        "CURRENCY" => "RUB",
    );

    $obPrice->Add($arFieldsPrice, true);

    //pre($PRODUCT_ID, 1);
}