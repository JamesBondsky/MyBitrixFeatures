<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="slick_slider_goods slider-good visually-hidden">
    <? foreach ($arResult["ITEMS"] as $key => $arItem) {
        $strTitle = (
        isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
            ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
            : $arItem['NAME']
        ); ?>
        <article class="slider-good-item">
            <div class="card-header">
                <? if (isset($arItem['PROPERTIES']['STICKER']) && !empty($arItem['PROPERTIES']['STICKER']['VALUE'])) { ?>
                    <div class="card-hit card-hit-<?=$arItem['PROPERTIES']['STICKER']['VALUE_XML_ID']?>">
                        <?= $arItem['PROPERTIES']['STICKER']['VALUE'] ?>
                    </div>
                <? } ?>
                <div class="price">

                    <?
                    if (isset($arItem['MIN_PRICE']) && !empty($arItem['MIN_PRICE'])) {
                        if ($arItem['MIN_PRICE']["DISCOUNT_VALUE"] < $arItem['MIN_PRICE']["VALUE"]):?>
                            <span class="new_price"><?= $arItem['MIN_PRICE']["PRINT_DISCOUNT_VALUE"] ?></span>
                            <span class="old_price"><?= $arItem['MIN_PRICE']["PRINT_VALUE"] ?></span>
                        <? else: ?>
                            <span class="new_price"><?= $arItem['MIN_PRICE']["PRINT_VALUE"] ?></span>
                        <?endif;
                    } else {
                        foreach ($arItem["PRICES"] as $priceCode => $arPrices):?>
                            <? if ($arPrices["DISCOUNT_VALUE"] < $arPrices["VALUE"]): ?>
                                <span class="new_price"><?= $arPrices["PRINT_DISCOUNT_VALUE"] ?></span>
                                <span class="old_price"><?= $arPrices["PRINT_VALUE"] ?></span>
                            <? else: ?>
                                <span class="new_price"><?= $arPrices["PRINT_VALUE"] ?></span>
                            <? endif ?>
                        <?endforeach;
                    }
                    ?>
                </div>
            </div>

            <div class="card">
                <img src="<?= $arItem["DETAIL_PICTURE"]["SRC"] ?>" title="<?= $strTitle ?>">
                <span class="good-name">
                            <?= $arItem["NAME"] ?>
                        </span>
            </div>
            <div class="card-footer">
                <div class="text-remaning">До конца акции осталось:</div>
                <div class="card-footer-timer">
                    <i class="countdown_icon"></i>
                    <div class="countdown__dial hours">
                        <span class="numbers">00</span>
                        <span class="captions">часов</span>
                    </div>
                    <div class="countdown__dial minutes">
                        <span class="numbers">20</span>
                        <span class="captions">минут</span>
                    </div>
                    <div class="countdown__dial seconds">
                        <span class="numbers">00</span>
                        <span class="captions">секунд</span>
                    </div>
                </div>
            </div>
            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="good-detail_link">
                <span class="visually-hidden">Перейти на страницу товара</span>
            </a>
        </article>
    <? } ?>
</div>