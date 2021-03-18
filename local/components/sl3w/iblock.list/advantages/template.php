<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

<h2 class="section__title advantages__title"><?=$arParams['BLOCK_H2_TITLE']?></h2>
<div class="advantages__list">
    <?foreach($arResult["ITEMS"] as $arItem) {?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="advantages__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <div class="advantages__text">
                <?= $arItem["PREVIEW_TEXT"];?>
            </div>
            <img class="advantages__image" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?= $arItem["NAME"]?>"
                 title="<?= $arItem["NAME"]?>">
        </div>
    <?}?>
</div>