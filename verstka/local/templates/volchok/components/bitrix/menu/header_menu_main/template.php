<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if (!empty($arResult)) { ?>


    <ul class="menu__main">
        <?
        foreach ($arResult as $k => $arItem) {
            if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                continue;
            ?>
            <li class="menu__main-item"><a class="menu__main-link <?=$arParams["CSS_CLASSES_FOR_ITEMS"][$k]?>"
                                           href="<?=$arItem["LINK"]?>>"><?=$arItem["TEXT"]?></a></li>
        <? } ?>
    </ul>
<? } ?>