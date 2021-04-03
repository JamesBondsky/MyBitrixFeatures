<?php
//скрипт создает похожие товары из массива
define('RESIZE_PREVIEW', 1);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("catalog");
CModule::IncludeModule("iblock");
CModule::IncludeModule("file");

$obPrice = new CPrice();
$el = new CIBlockElement();

$products = array();
$products[] = array("LENGTH" => 3, "WIDTH" => 2, "DEPTH" => 1.5, "VOLUME" => 7.7, "price" => 62300, 'image' => 'бассейны.png');
$products[] = array("LENGTH" => 3, "WIDTH" => 2, "DEPTH" => 1.5, "VOLUME" => 7.7, "price" => 62300, 'image' => 'бассейны.png');

$mediaLb = getUploadedImagesMediaLibrary("iblock");

$codeAr = array();
$ii = 0;
foreach ($products as $product) {
    $ii++;
    $prodName = "Овальный бассейн полипропиленовый, " . $product["VOLUME"] . " м3";

    $image = getImageDataByFileName($product['image'], $mediaLb);

    $price = $product['price'];

    $PROP = array();
    $PROP['SIZES'] = $product["LENGTH"] . ' x ' . $product["WIDTH"] . ' x ' . $product["DEPTH"] . ' м';
    $PROP['LENGTH'] = $product["LENGTH"] * 1000;
    $PROP['WIDTH'] = $product["WIDTH"] * 1000;
    $PROP['DEPTH'] = $product["DEPTH"] * 1000;
    $PROP['VOLUME'] = $product["VOLUME"] . ' м3';
    $PROP['FORMA'] = "Овальный";
    $PROP['MATERIAL'] = "Полипропилен";

    $arParamsT = array("replace_space"=>"_","replace_other"=>"_");
    $code = CUtil::translit(htmlspecialchars_decode($prodName), "ru", $arParamsT);
    //на случай повторного кода
    $code = in_array($code, $codeAr) ? $code . '-' . $ii : $code;

    $prodName = htmlspecialchars_decode($prodName);

    $arFields = array(
        "IBLOCK_ID" => 2,
        'IBLOCK_SECTION_ID' => 25,
        "NAME" => $prodName,
        "CODE" => $code,
        //"DETAIL_TEXT" => htmlspecialchars_decode($prodDetail),
        //"DETAIL_TEXT_TYPE" => 'html',
        "DETAIL_PICTURE" => $image,
        "PREVIEW_PICTURE" => $image,
        "PROPERTY_VALUES" => $PROP
    );

    $PRODUCT_ID = $el->Add($arFields);

    if($PRODUCT_ID) {
        $codeAr[] = $code;
        if (RESIZE_PREVIEW) {
            $rsElements = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 2, "ID" => $PRODUCT_ID));

            $rsElement = new CIBlockElement;

            $iHeight = 200;
            $iWidth = 300;

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
                    $arLoadProductArray = array(
                        "PREVIEW_PICTURE" => CFile::MakeFileArray($arPreview["src"]),
                    );
                    if ($rsElement->Update($arElement["ID"], $arLoadProductArray)) {
                        echo "Элемент {$arElement["NAME"]} обновлён.\n";
                    }
                }
            }
        }

        $arFieldsCatalog = array(
            "ID" => $PRODUCT_ID,
            "AVAILABLE " => "Y",
            "QUANTITY" => 999999,
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

        pre($PRODUCT_ID);
    }
}

function getUploadedImagesMediaLibrary($subdir = 'medialibrary') {
    $mediaLibrary = array();

    $res = CFile::GetList();
    while($res_arr = $res->GetNext()) {
        if (strpos($res_arr["SUBDIR"], $subdir) !== false) {
            $mediaLibrary[$res_arr['ID']]['ORIGINAL'] = $res_arr["ORIGINAL_NAME"];
            $mediaLibrary[$res_arr['ID']]['FILE'] = $res_arr["FILE_NAME"];
        }
    }
    return $mediaLibrary;
}

function getImageDataByFileName($imagePathName, $mediaLibrary = false)
{
    if(!$mediaLibrary) {
        $mediaLibrary = getUploadedImagesMediaLibrary();
    }

//    $info = pathinfo($imagePathName);
//    $image = $info['basename'];
    $image = $imagePathName;

    foreach ($mediaLibrary as $idIm => $item) {
        if ($item['ORIGINAL'] == $image) {
            $image = CFile::GetPath($idIm);
            break;
        }
    }
    return CFile::MakeFileArray($image);
}