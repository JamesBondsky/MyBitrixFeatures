<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Интернет-магазин \"Одежда\"");

global $mainSliderFilter;
$mainSliderFilter = array('PROPERTY_BANNER_TYPE' => 319);
?>
    <section class="notice">
        <div class="notice__container">
            <?$APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "main_slider",
                array(
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "A",
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "DISPLAY_DATE" => "N",
                    "DISPLAY_NAME" => "N",
                    "DISPLAY_PICTURE" => "N",
                    "DISPLAY_PREVIEW_TEXT" => "N",
                    "DISPLAY_TOP_PAGER" => "N",
                    "FIELD_CODE" => array(
                        0 => "ID",
                        1 => "CODE",
                        2 => "XML_ID",
                        3 => "NAME",
                        4 => "TAGS",
                        5 => "SORT",
                        6 => "PREVIEW_TEXT",
                        7 => "PREVIEW_PICTURE",
                        8 => "DETAIL_TEXT",
                        9 => "DETAIL_PICTURE",
                        10 => "DATE_ACTIVE_FROM",
                        11 => "ACTIVE_FROM",
                        12 => "DATE_ACTIVE_TO",
                        13 => "ACTIVE_TO",
                        14 => "SHOW_COUNTER",
                        15 => "SHOW_COUNTER_START",
                        16 => "IBLOCK_TYPE_ID",
                        17 => "IBLOCK_ID",
                        18 => "IBLOCK_CODE",
                        19 => "IBLOCK_NAME",
                        20 => "IBLOCK_EXTERNAL_ID",
                        21 => "DATE_CREATE",
                        22 => "CREATED_BY",
                        23 => "CREATED_USER_NAME",
                        24 => "TIMESTAMP_X",
                        25 => "MODIFIED_BY",
                        26 => "USER_NAME",
                        27 => "",
                    ),
                    "FILTER_NAME" => "mainSliderFilter",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "IBLOCK_ID" => "4",
                    "IBLOCK_TYPE" => "design",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "INCLUDE_SUBSECTIONS" => "N",
                    "MESSAGE_404" => "",
                    "NEWS_COUNT" => "20",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => "",
                    "PAGER_TITLE" => "Слайды",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "PROPERTY_CODE" => array(
                        0 => "URL_STRING",
                        1 => "BANNER_TYPE",
                        2 => "",
                    ),
                    "SET_BROWSER_TITLE" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_STATUS_404" => "N",
                    "SET_TITLE" => "N",
                    "SHOW_404" => "N",
                    "SORT_BY1" => "ACTIVE_FROM",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER1" => "DESC",
                    "SORT_ORDER2" => "ASC",
                    "STRICT_SECTION_CHECK" => "N",
                    "COMPONENT_TEMPLATE" => ".default",
                    "TEMPLATE_THEME" => "blue",
                    "MEDIA_PROPERTY" => "",
                    "SLIDER_PROPERTY" => "",
                    "SEARCH_PAGE" => "/search/",
                    "USE_RATING" => "N",
                    "USE_SHARE" => "N"
                ),
                false
            );?>
            <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.top",
	"goods_slider",
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"BASKET_URL" => "/personal/basket.php",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPATIBLE_MODE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[{\"CLASS_ID\":\"CondIBProp:6:29\",\"DATA\":{\"logic\":\"Equal\",\"value\":22}}]}",
		"DETAIL_URL" => "",
		"DISPLAY_COMPARE" => "N",
		"ELEMENT_COUNT" => "9",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "6",
		"IBLOCK_TYPE" => "catalog",
		"LINE_ELEMENT_COUNT" => "3",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"OFFERS_LIMIT" => "5",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"ROTATE_TIMER" => "30",
		"SECTION_URL" => "",
		"SEF_MODE" => "N",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PAGINATION" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "Y",
		"TEMPLATE_THEME" => "blue",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"VIEW_MODE" => "SECTION",
		"COMPONENT_TEMPLATE" => "goods_slider"
	),
	false
);?>
        </div>
    </section>
    <section class="search">
        <div class="search__container tabs search__tabs">
            <div class="tabs__list-wrap">
                <ul class="tabs__list  tabs__list-no_underline">
                    <li class="tabs__item">
                        <a class="tabs__link" href="#search-vin">Поиск по Vin</a>
                    </li>
                    <li class="tabs__item">
                        <a class="tabs__link tabs__link--active" href="#search-mark">Поиск по марке</a>
                    </li>
                    <li class="tabs__item">
                        <a class="tabs__link" href="#search-name">Поиск по названию товара</a>
                    </li>
                    <li class="tabs__item">
                        <a class="tabs__link" href="#search-article">Поиск по артикулу</a>
                    </li>
                </ul>
            </div>
            <section class="tabs__panel" id="search-vin">
                <form method="post" action="#">
                    <div class="search__fields">
                        <input type="search" placeholder="Введите vin код">
                        <button class="button" type="submit"><span class="search__button-text">Искать</span></button>
                    </div>
                </form>
            </section>
            <section class="tabs__panel tabs__panel--active" id="search-mark">
                <form method="post" action="#">
                    <div class="search__fields">
                        <input type="search" placeholder="Введите марку автомобиля">
                        <button class="button" type="submit"><span class="search__button-text">Искать</span></button>
                    </div>
                </form>
            </section>
            <section class="tabs__panel" id="search-name">
                <form method="post" action="#">
                    <div class="search__fields">
                        <input type="search" placeholder="Введите название товара">
                        <button class="button" type="submit"><span class="search__button-text">Искать</span></button>
                    </div>
                </form>
            </section>
            <section class="tabs__panel" id="search-article">
                <form method="post" action="#">
                    <div class="search__fields">
                        <input type="search" placeholder="Введите артикул">
                        <button class="button" type="submit"><span class="search__button-text">Искать</span></button>
                    </div>
                </form>
            </section>
        </div>
    </section>
    <section class="categories">
        <?
        global $popularSectionsFilter;
        $popularSectionsFilter = array("!UF_MAIN_POPULAR" => false);
        ?>
        <?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "main_popular", Array(
            "ADD_SECTIONS_CHAIN" => "Y",	// Включать раздел в цепочку навигации
            "CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
            "CACHE_GROUPS" => "Y",	// Учитывать права доступа
            "CACHE_TIME" => "36000000",	// Время кеширования (сек.)
            "CACHE_TYPE" => "A",	// Тип кеширования
            "COUNT_ELEMENTS" => "N",	// Показывать количество элементов в разделе
            "COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",	// Показывать количество
            "FILTER_NAME" => "popularSectionsFilter",	// Имя массива со значениями фильтра разделов
            "IBLOCK_ID" => "6",	// Инфоблок
            "IBLOCK_TYPE" => "catalog",	// Тип инфоблока
            "SECTION_CODE" => "",	// Код раздела
            "SECTION_FIELDS" => array(	// Поля разделов
                0 => "",
                1 => "",
            ),
            "SECTION_ID" => "",	// ID раздела
            "SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
            "SECTION_USER_FIELDS" => array(	// Свойства разделов
                0 => "UF_MAIN_POPULAR",
                1 => "",
            ),
            "SHOW_PARENT_NAME" => "Y",	// Показывать название раздела
            "TOP_DEPTH" => "2",	// Максимальная отображаемая глубина разделов
            "VIEW_MODE" => "LINE",	// Вид списка подразделов
        ),
            false
        );?>
    </section>
    <section class="popular">
        <div class="popular__container">
            <div class="popular__tabs">
                <h2>Популярные товары</h2>
                <div class="tabs__list-wrap">
                    <ul class="tabs__list">
                        <li class="tabs__item">
                            <a class="tabs__link tabs__link--active" href="#zapchasti">запчасти</a>
                        </li>
                        <li class="tabs__item">
                            <a class="tabs__link" href="#avtohimiya">автохимия</a>
                        </li>
                        <li class="tabs__item">
                            <a class="tabs__link" href="#shiny_i_diski">шины и диски</a>
                        </li>
                        <li class="tabs__item">
                            <a class="tabs__link" href="#avtoelektronika">автоэлектроника</a>
                        </li>
                        <li class="tabs__item">
                            <a class="tabs__link" href="#instrumenty">инструменты</a>
                        </li>
                        <li class="tabs__item">
                            <a class="tabs__link" href="#aksessuary">аксессуары и другое</a>
                        </li>
                    </ul>
                </div>
                <section class="tabs__panel tabs__panel--active" id="zapchasti">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR."/include/main_popular_zapchasti.php"
                        ),
                        false
                    );?>
                    <div class="popular__footer">
                        <a class="popular__link-more">Смотреть еще</a>
                    </div>
                </section>
                <section class="tabs__panel" id="aksessuary">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR."/include/main_popular_aksessuary.php"
                        ),
                        false
                    );?>
                </section>
            </div>
        </div>
    </section>
    <div class="full-screen-banner">
        <div class="full-screen-banner__container">
            <a class="full-screen-banner__element" style="background-image: url('src/banner.png')">
                <!--                <img class="full-screen-banner__image" src="src/banner.png">-->
            </a>
        </div>
    </div>
    <section class="suggestion">
        <div class="suggestion__container">
            <div class="suggestion__tabs">
                <h2>С этим товаром покупают</h2>
                <div class="tabs__list-wrap">
                    <ul class="tabs__list">
                        <li class="tabs__item">
                            <a class="tabs__link" href="#zapchasti-sug">запчасти</a>
                        </li>
                        <li class="tabs__item">
                            <a class="tabs__link" href="#avtohimiya-sug">автохимия</a>
                        </li>
                        <li class="tabs__item">
                            <a class="tabs__link tabs__link--active" href="#shiny_i_diski-sug">шины и диски</a>
                        </li>
                        <li class="tabs__item">
                            <a class="tabs__link" href="#avtoelektronika-sug">автоэлектроника</a>
                        </li>
                        <li class="tabs__item">
                            <a class="tabs__link" href="#instrumenty-sug">инструменты</a>
                        </li>
                        <li class="tabs__item">
                            <a class="tabs__link" href="#aksessuary-sug">аксессуары и другое</a>
                        </li>
                    </ul>
                </div>
                <section class="tabs__panel tabs__panel--active" id="shiny_i_diski-sug">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR."/include/main_suggestion_shiny.php"
                        ),
                        false
                    );?>
                </section>
            </div>
        </div>
    </section>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>