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

<h2 class="section__title examples__title"><?= $arParams['BLOCK_H2_TITLE'] ?></h2>
<div class="examples-carousel owl-carousel">
    <? foreach ($arResult["ITEMS"] as $arItem) { ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="examples__slide" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
            <div class="img_zoom-wrapper">
                <img class="examples__slide-img" src="<?= $arItem["DETAIL_PICTURE"]["SRC"] ?>"
                     alt="<?= $arItem["NAME"]; ?>"
                     title="<?= $arItem["NAME"]; ?>">
                <div class="img-zoom"></div>
            </div>
            <span class="examples__slide-text"><?= $arItem["DETAIL_TEXT"]; ?></span>
        </div>
    <? } ?>
</div>
<div class="examples-carousel-nav_container default-carousel-arrows-nav_container">
</div>
<div class="examples-carousel-dots_container-wrapper">
    <div class="examples-carousel-dots_container">
    </div>
    <a class="examples_all-links no-gradient" href="<?= $arParams['ALL_EXAMPLES_LINK'] ?>">
        <span class="orange-gradient"><?= $arParams['ALL_EXAMPLES_TEXT'] ?></span>
        <div class="link-go_arrow"></div>
    </a>
</div>