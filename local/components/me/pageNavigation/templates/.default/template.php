<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);
//количество на странице
$countOnPage = $arParams['COUNTONPAGE'];
//номер текущей страницы
$pageNum = $arParams['PAGEN'];
//количество элементов
$elementsCount = $arParams['ELEMENTSCOUNT'];

//ссылка на страницу
$curURL = $APPLICATION->GetCurPage();

//общее количество
if ($elementsCount % $countOnPage == 0)
    $countPage = intdiv($elementsCount, $countOnPage);
else
    $countPage = intdiv($elementsCount, $countOnPage) + 1;
?>

<div class="wrap_pagination module-pagination">
    <ul class="pagination">
        <? if ($pageNum > 1) {
            if ($pageNum > 2) {
                $href = $curURL . '?PAGEN=' . ($pageNum - 1);
            } else {
                $href = $curURL;
            }
            ?>
            <li class="prev"><a
                        href="<?= $href ?>"><?= CAllcorp2::showIconSvg('cabinet', SITE_TEMPLATE_PATH . '/images/svg/Arrow_left_black_sm.svg'); ?></a>
            </li>
            <link rel="prev" href="<?= $href; ?>">
            <link rel="canonical" href="<?= $curURL ?>"/>
        <? } ?>

        <? for ($i = 1; $i <= $countPage; $i++) { ?>
            <? if ($i == $pageNum) { ?>
                <li class="active"><span><?= $i ?></span></li>
            <? } else {
                if ($i != 1) { ?>
                    <li>
                        <a href="<?= $curURL ?>?PAGEN=<?= $i ?>"><?= $i ?></a>
                    </li>
                <? } else { ?>
                    <li>
                        <a href="<?= $curURL ?>"><?= $i ?></a>
                    </li>
                <? }
            }
        } ?>

        <? if ($pageNum < $countPage) { ?>
            <? // next?>
            <? $href = $curURL . '?PAGEN=' . ($pageNum + 1); ?>
            <li class="next"><a
                        href="<?= $href ?>"><?= CAllcorp2::showIconSvg('cabinet', SITE_TEMPLATE_PATH . '/images/svg/Arrow_right_black_sm.svg'); ?></a>
            </li>
            <link rel="next" href="<?= $href; ?>">
        <? } ?>
    </ul>
</div>