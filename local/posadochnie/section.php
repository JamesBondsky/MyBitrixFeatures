<?
        if (!\Bitrix\Main\Context::getCurrent()->getRequest()->get("PAGEN_1")) {
            $listPosads = getListOfElementsWithPropertiesAsArray(IBLOCK_ID_POSAD);
            foreach ($listPosads as $posad) {
                if ($APPLICATION->GetCurPage() == $posad['PROPS']['FILTER_URL']['VALUE']) {
                    $curPageIsPosad = true;
                    $curPosadProps = $posad['PROPS'];
                    break;
                } elseif ($arCurSection["ID"] == $posad['PROPS']['SECTION']['VALUE']) {
                    $posadsForCurrent[] = $posad;
                }
            }
            if ($curPageIsPosad) {
                $iDontCareThisIsMySEO = true;
            } // вывод ссылок на посадочную между фильтром и списком товаров
            elseif ($posadsForCurrent) {
                foreach ($posadsForCurrent as $posadochka) {
                    if ($posadochka['PROPS']['SHOW_CATALOG']['VALUE']) { ?>
                        <a href="<?= $posadochka['PROPS']['FILTER_URL']['VALUE'] ?>"><?= $posadochka['TITLE'] ?></a><br>
                        <?
                    }
                }
            }
        }
?>

<?
if ($iDontCareThisIsMySEO) {
    if ($curPosadProps['SEO_H1']['VALUE'])
        $APPLICATION->SetTitle($curPosadProps['SEO_H1']['VALUE']);
    if ($curPosadProps['SEO_TITLE']['VALUE'])
        $APPLICATION->SetPageProperty("title", $curPosadProps['SEO_TITLE']['VALUE']);
    if ($curPosadProps['SEO_DESCRIPTION']['VALUE'])
        $APPLICATION->SetPageProperty("description", $curPosadProps['SEO_DESCRIPTION']['VALUE']);
    if ($curPosadProps['SEO_KEYWORDS']['VALUE'])
        $APPLICATION->SetPageProperty("keywords", $curPosadProps['SEO_KEYWORDS']['VALUE']);
}
?>