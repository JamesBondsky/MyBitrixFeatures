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

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

?>
<div class="categories__container">
    <?
    if (0 < $arResult["SECTIONS_COUNT"]) {
        ?>
        <ul class="categories__list">
            <?
            foreach ($arResult['SECTIONS'] as &$arSection) {
                $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
                $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

                if (false === $arSection['PICTURE'])
                    $arSection['PICTURE'] = array(
                        'SRC' => "/",
                        'ALT' => (
                        '' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
                            ? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
                            : $arSection["NAME"]
                        ),
                        'TITLE' => (
                        '' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
                            ? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
                            : $arSection["NAME"]
                        )
                    );
                ?>
            <li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>" class="categories__item">
                <a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"
                   class="categories__wrap-link"
                   title="<? echo $arSection['PICTURE']['TITLE']; ?>">
                    <h3 class="categories__title"><?= $arSection["NAME"] ?></h3>
                    <div class="categories__icon">
                        <img width="<?= $arSection['PICTURE']['WIDTH'] ?>"
                             height="<?= $arSection['PICTURE']['HEIGHT'] ?>"
                             src="<?= $arSection['PICTURE']['SRC'] ?>">
                    </div>
                    <span class="categories__link">
                            Перейти
                        </span>
                </a>
                </li><?
            }
            unset($arSection);
            ?>
        </ul>
        <?
    }
    ?>
</div>