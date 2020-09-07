<?php
define('DEBUG_THIS', 0);

if (DEBUG_THIS) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
        die();
}

if (CModule::IncludeModule("iblock")) {
    $arFilter = array('IBLOCK_ID' => 25, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array(), array());

    while ($ob = $res->GetNextElement()) {

        $arFields = $ob->GetFields();
        //$arProp = $ob->GetProperties();

        if (in_array($arFields['IBLOCK_SECTION_ID'], array(5129, 5156, 5132, 5131))) {
            pre($arFields, 1);

            //if ($arProp["TEMPLATE_DESCRIPTION"]["VALUE"] == "Y") {

                $href = 'https://klampi.local/catalog/item/' . $arFields['CODE'] . "-" . $arFields['ID'] . '/';
                pre("ID = " . $arFields['ID'] . ". " . $arFields['NAME'] . ' - <a target="_blank" href="' . $href . '">' . $href . '</a>');

                //$brandID = $arProp["BRAND"]["VALUE"];
                //$brand = getElementFieldsByID($brandID);
                $text = $arFields['DETAIL_TEXT'].'<h3 id="advantages">
	 Преимущества когтеточек:
</h3>
<ul>
	<li><b>Устойчивая платформа.</b> Размер, вес и форма платформы рассчитаны на самого упитанного и игривого питомца. Проверена многочисленными краш-тестами.</li>
	<li><b>Не царапает пол.</b> Закрыты все элементы ДСП - это эстетично, а также не дает когтеточке царапать пол.</li>
	<li><b>Плотная обмотка.</b> Мы не экономим на обмотке, крутим плотно, поэтому наши когтеточки служат долго.</li>
	<li><b>Долго сохраняет внешний вид.</b> Для обивки мы используем качественный ковролин, который долго сохраняет внешний вид.</li>
</ul>';

                //pre($text, 1);

                $arLoadProductArray = array(
                    "DETAIL_TEXT" => $text,
                    "DETAIL_TEXT_TYPE" => "html"
                );

                $rsElement = new CIBlockElement;
                if ($rsElement->Update($arFields['ID'], $arLoadProductArray)) {
                    echo "Элемент {$arFields["NAME"]} обновлён.\n";
                }
                //break;
            //}
        }
    }
}