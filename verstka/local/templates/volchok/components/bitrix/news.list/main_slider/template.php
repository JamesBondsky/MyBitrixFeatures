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
$this->addExternalJS("/script.js");
?>
<div class="slick_slider_images">
    <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="slider_item">
            <? if (!empty($arItem["PROPERTIES"]["URL_STRING"]["VALUE"])) { ?>
            <a href="<?= $arItem["PROPERTIES"]["URL_STRING"]["VALUE"] ?>"
               target="<?= $arItem["PROPERTIES"]["TARGETS"]["VALUE_XML_ID"] ?>">
                <? } ?>
                <div class="slider_content visually-hidden"
                     style="background-image: url(<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>)"></div>
                <? if (!empty($arItem["PROPERTIES"]["URL_STRING"]["VALUE"])) { ?>
            </a>
        <? } ?>
        </div>
    <? endforeach; ?>
</div>
