<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/me/iblockAndOther/iBlockWorking.php");

$this->setFrameMode(true);

//количество на странице
$countOnPage = $arParams['COUNTONPAGE'];
//номер текущей страницы
$pageNum = $arParams['PAGEN'];
//начальный индекс фото
$start = $countOnPage * ($pageNum - 1) + 1;
//конечный индекс фото
$end = $countOnPage * $pageNum;

//все фото
$photos = getPropertyValueByElementID($arParams['ELEMENT_ID'], 'PHOTOS');
//$photos = getPropertyValuesOfElement($arParams['IBLOCK_ID'], $arParams['ELEMENT_ID'], 'PHOTOS');

//только фото для вывода
$photos2 = array_slice($photos, $start - 1, $end - $start + 1);
?>
<div class="photos_body">
    <?
    foreach ($photos2 as $key => $value) {
        $urlToImage = CFile::GetPath($value);
        ?>
        <a class="fancybox_photos photos_body_link" rel="group" href="<?=$urlToImage?>">
            <img class="photos_body_link_img" src="<?=$urlToImage?>" alt="">
        </a>
        <?
    } ?>
</div>

<?$APPLICATION->IncludeComponent(
    "me:pageNavigation",
    "",
    Array(
        'PAGEN' => $pageNum,
        'COUNTONPAGE' => $countOnPage,
        'ELEMENTSCOUNT' => count($photos),
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
    )
);?>