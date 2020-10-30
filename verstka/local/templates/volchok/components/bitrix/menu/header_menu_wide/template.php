<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if (!empty($arResult)) { ?>
    <div class="page-header__bottom">
        <nav class="page-header__nav">
            <ul class="nav-list">
                <?
                foreach ($arResult as $arItem) {
                    if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                        continue;
                    ?>
                    <? if ($arItem["SELECTED"]) { ?>
                        <li class="nav-list-item selected"><?= $arItem["TEXT"] ?></li>
                    <? } else { ?>
                        <li class="nav-list-item"><a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a></li>
                    <? }
                } ?>
            </ul>
        </nav>
    </div>
<? } ?>