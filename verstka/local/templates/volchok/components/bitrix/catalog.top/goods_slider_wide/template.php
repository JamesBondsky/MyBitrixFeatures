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
<?
//pre($arResult['ITEMS']);
?>

<div class="slick_slider slick_slider_goods-wide">
    <? foreach ($arResult["ITEMS"] as $key => $arItem) {
        $mainId = $this->GetEditAreaId($arItem['ID']) . '_basket_actions';
        $strTitle = (
        isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]) && '' != isset($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
            ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
            : $arItem['NAME']
        ); ?>
        <div class="good_item">
            <div class="good-attributes">
                <? if (isset($arItem['PROPERTIES']['STICKER']) && !empty($arItem['PROPERTIES']['STICKER']['VALUE'])) { ?>
                    <div class="card-hit card-hit-<?= $arItem['PROPERTIES']['STICKER']['VALUE_XML_ID'] ?>">
                        <?= $arItem['PROPERTIES']['STICKER']['VALUE'] ?>
                    </div>
                <? } ?>
                <div class="good-attributes__stickers">
                    <button class="good-attribute favourite" type="button"></button>
                    <? if (!empty($arItem['PROPERTIES']['STICKERGOOD']['VALUE'])) { ?>
                        <img class="good-attribute good__favourite"
                             src="<?= $arItem['PROPERTIES']['STICKERGOOD']['PICTURE']['PREVIEW_PICTURE']['src'] ?>">
                    <? } ?>
                </div>
            </div>
            <a class="good_fast-view" href="#">
                быстрый просмотр
            </a>
            <div class="good-picture">
                <img src="<?= $arItem["DETAIL_PICTURE"]["SRC"] ?>" title="<?= $strTitle ?>">
                <span class="good-title"><?= $arItem["NAME"] ?></span>
            </div>

            <? if (!$arItem['PRODUCT']['QUANTITY']) { ?>
                <div class="good-price-none">
                        <span class="good-none">
                            нет в наличии
                        </span>
                    <a class="good-none-notice" href="#">Сообщить о поступлении</a>
                </div>
            <? } else { ?>
                <span class="good-price">
                <?
                if (isset($arItem['MIN_PRICE']) && !empty($arItem['MIN_PRICE'])) { ?>
                    <?= $arItem['MIN_PRICE']["PRINT_VALUE"] ?>
                    <?
                } else {
                    foreach ($arItem["PRICES"] as $priceCode => $arPrices):?>
                        <?= $arPrices["PRINT_VALUE"] ?>
                    <?endforeach;
                }
                ?>
                </span>
                <a id="<?= $mainId ?>" class="good-basket__link-buy" title="Добавить товар в корзину"
                   data-prod-id="<?= $arItem['ID'] ?>"
                   data-url="<?= SITE_TEMPLATE_PATH ?>/ajax/addToCart.php">
                    <div class="good-basket">
                        <img src="<?= SITE_TEMPLATE_PATH ?>/src/basket-buy.svg">
                    </div>
                    <span class="visually-hidden">Добавить товар в корзину</span>
                </a>
            <? } ?>
            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="good-detail_link">
                <span class="visually-hidden">Перейти на страницу товара</span>
            </a>
        </div>
    <? } ?>
</div>