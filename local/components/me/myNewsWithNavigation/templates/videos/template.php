<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/me/iblockAndOther/iBlockWorking.php");

$this->setFrameMode(true);

//количество на странице
$countOnPage = $arParams['COUNTONPAGE'];
//номер текущей страницы
$pageNum = $arParams['PAGEN'];
//начальный индекс видео
$start = $countOnPage * ($pageNum - 1) + 1;
//конечный индекс видео
$end = $countOnPage * $pageNum;

//все видео
//$videos = getPropertyValuesOfElement($arParams['IBLOCK_ID'], $arParams['ELEMENT_ID'], 'VIDEO');
$videos = getPropertyValueByElementID($arParams['ELEMENT_ID'], 'VIDEO');

//только видео для вывода
$videos2 = array_slice($videos, $start - 1, $end - $start + 1);
?>
<div class="video_body">
    <?
    foreach ($videos2 as $key => $value) {
        ?>
        <iframe class="video_body_item" src="https://www.youtube.com/embed/<?= $value ?>?rel=0" frameborder="0"
                allow="autoplay; encrypted-media" allowfullscreen></iframe>
        <?
    } ?>
</div>

<?$APPLICATION->IncludeComponent(
    "me:pageNavigation",
    "",
    Array(
        'PAGEN' => $pageNum,
        'COUNTONPAGE' => $countOnPage,
        'ELEMENTSCOUNT' => count($videos),
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
    )
);?>