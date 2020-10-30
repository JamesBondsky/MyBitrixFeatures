<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if (!empty($arResult)) { ?>
    <?
    foreach ($arResult as $k => $arItem) {
        if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
            continue;
        if ($k == 0) {
            ?>

            <h2><?= $arItem["TEXT"] ?></h2>
            <button type="button" name="button">
                <span class="visually-hidden"><?= $arItem["TEXT"] ?></span>
            </button>
            <ul>
            <?
        } else { ?>
            <li>
                <a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
            </li>
            <?
        }
    } ?>
    </ul>
<? } ?>