<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>

<? if (!empty($arResult)) { ?>

    <ul class="menu__categories">
        <?
        foreach ($arResult as $arItem) {
            if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                continue;
            ?>
            <li><a href="<?= $arItem["LINK"] ?>>"><?= $arItem["TEXT"] ?></a></li>
        <? } ?>
    </ul>
<? } ?>