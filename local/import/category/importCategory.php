<?php
define('DEBUG_THIS', 1);

if (DEBUG_THIS) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
}
$bs = new CIBlockSection;

$categories = json_decode(file_get_contents("oc_category.json"), 1)[2]['data'];
$categoriesDescr = json_decode(file_get_contents("oc_category_description.json"), 1)[2]['data'];

pre($categoriesDescr, 1);

$arrayCat = array();
$arrayDont = array();

foreach ($categories as $category) {
    $catId = $category['category_id'];
    //$catImage = $category['image'];
    $catParent_id = $category['parent_id'];

    foreach ($categoriesDescr as $categoryDescr) {
        if ($catId == $categoryDescr['category_id']) {
            $catName = $categoryDescr['name'];
            $catDetail = $categoryDescr['description'];
            break;
        }
    }

    if ($catParent_id == 0) {
        $arFields = Array(
            "ID" => $catId,
            "IBLOCK_SECTION_ID" => $catParent_id * 110,
            "IBLOCK_ID" => 25,
            "NAME" => $catName,
            //"PICTURE" => $catImage,
            "DESCRIPTION" => $catDetail,
            "DESCRIPTION_TYPE" => 'html'
        );
        $int = $bs->Add($arFields);
        $arrayCat[$catId] = $int;
    } else {
        if (key_exists($catParent_id, $arrayCat)) {
            $arFields = Array(
                "ID" => $catId,
                "IBLOCK_SECTION_ID" => $arrayCat[$catParent_id],
                "IBLOCK_ID" => 25,
                "NAME" => $catName,
                //"PICTURE" => $catImage,
                "DESCRIPTION" => $catDetail,
                "DESCRIPTION_TYPE" => 'html'
            );
            $int = $bs->Add($arFields);
            $arrayCat[$catId] = $int;
        } else {
            $arrayDont[$catId] = array('name' => $catName, 'parent_id' => $catParent_id, 'description' => $catDetail);
        }
    }
}

//pre($arrayDont);
$i = 0;

while(true && $i < 500) {
    if (empty($arrayDont))
        break;
    else {
        checkDont($arrayDont, $arrayCat, $bs);
        $i++;
    }
}

function checkDont(&$arr, $arrayCat, $bs) {
    foreach ($arr as $id => $cat) {
        if (key_exists($cat['parent_id'], $arrayCat)) {
            $arFields = Array(
                "ID" => $id,
                "IBLOCK_SECTION_ID" => $arrayCat[$cat['parent_id']],
                "IBLOCK_ID" => 25,
                "NAME" => $cat['name'],
                "DESCRIPTION" => $cat['description'],
                "DESCRIPTION_TYPE" => 'html'
            );
            $int = $bs->Add($arFields);
            $arrayCat[$id] = $int;
            unset($arr[$id]);
        }
    }
}
