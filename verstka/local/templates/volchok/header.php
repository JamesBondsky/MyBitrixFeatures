<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);
?>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <? $APPLICATION->ShowHead(); ?>
    <title><? $APPLICATION->ShowTitle() ?></title>
</head>
<?

use Bitrix\Main\Page\Asset;

$_asset = Asset::getInstance();

$_asset->addCss(SITE_TEMPLATE_PATH . "/slick-1.8.1/slick-1.8.1/slick/slick.css");
$_asset->addCss(SITE_TEMPLATE_PATH . "/slick-1.8.1/slick-1.8.1/slick/slick-theme.css");
$_asset->addCss(SITE_TEMPLATE_PATH . "/normalize.css");
$_asset->addCss(SITE_TEMPLATE_PATH . "/template_styles.css");
$_asset->addCss(SITE_TEMPLATE_PATH . "/slider.css");
$_asset->addCss(SITE_TEMPLATE_PATH . "/styles.css");
$_asset->addJs(SITE_TEMPLATE_PATH . "/slick-1.8.1/slick-1.8.1/slick/slick.min.js");
$_asset->addJs(SITE_TEMPLATE_PATH . "/js/common.js");

$curPage = $APPLICATION->GetCurPage(true);
$mainPage = $curPage == SITE_DIR . "index.php";
?>
<!--<script type="text/javascript" src="slick-1.8.1/slick-1.8.1/slick/slick.min.js"></script>-->
<!--<script src="js/common.js"></script>-->
<body bgcolor="#FFFFFF">

<div id="panel">
    <? $APPLICATION->ShowPanel(); ?>
</div>
<header class="page-header">
    <div class="page-header__container">
        <div class="page-header__wrap">
            <div class="page-header__top">
                <div class="page-header__top-container">
                    <button class="mobile-menu__toogle">
                    <span class="mobile-menu__bar">
                    </span>
                    </button>
                    <a class="page-header__logo-img" href="/">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_DIR."include/header_company_logo.php"
                            ),
                            false
                        );?>
                    </a>
                    <div class="menu">
                        <ul class="menu__user">
                            <li class="menu__user-item"><a class="menu__user-link menu__user-link--log-in" href="#">Войти</a>
                            </li>
                            <li class="menu__user-item"><a class="menu__user-link menu__user-link--sign-up"
                                                           href="#">Регистрация</a>
                            </li>
                            <li class="menu__user-item"><a class="menu__user-link menu__user-link--favorites"
                                                           href="#">Избранное</a>
                            </li>
                        </ul>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "header_menu_main",
                            array(
                                "ALLOW_MULTI_SELECT" => "N",
                                "CHILD_MENU_TYPE" => "left",
                                "DELAY" => "N",
                                "MAX_LEVEL" => "1",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "ROOT_MENU_TYPE" => "top",
                                "USE_EXT" => "N",
                                "COMPONENT_TEMPLATE" => "header_menu_main",
                                //custom params
                                "CSS_CLASSES_FOR_ITEMS" => array("menu__main-link--shops", "menu__main-link--actions", "menu__main-link--delivery")
                            ),
                            false
                        ); ?>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "header_menu_wide_mobile",
                            array(
                                "ALLOW_MULTI_SELECT" => "N",
                                "CHILD_MENU_TYPE" => "left",
                                "DELAY" => "N",
                                "MAX_LEVEL" => "1",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "ROOT_MENU_TYPE" => "top_wide",
                                "USE_EXT" => "N",
                                "COMPONENT_TEMPLATE" => "header_menu_main_mobile"
                            ),
                            false
                        ); ?>
                        <a class="address" href="#">
                            <img src="<?= SITE_TEMPLATE_PATH ?>/src/gps.svg" alt="GPS">
                            <span class="address-text">
                                <?$APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "PATH" => SITE_DIR."/include/company_address.php"
                                    ),
                                    false
                                );?>
                            </span>
                        </a>
                    </div>

                    <?$APPLICATION->IncludeComponent(
                        "bitrix:sale.basket.basket.line",
                        "header_basket",
                        array(
                            "PATH_TO_BASKET" => SITE_DIR."personal/cart/",
                            "PATH_TO_PERSONAL" => SITE_DIR."personal/",
                            "SHOW_PERSONAL_LINK" => "N",
                            "SHOW_NUM_PRODUCTS" => "Y",
                            "SHOW_TOTAL_PRICE" => "Y",
                            "SHOW_PRODUCTS" => "N",
                            "POSITION_FIXED" =>"N",
                            "SHOW_AUTHOR" => "Y",
                            "PATH_TO_REGISTER" => SITE_DIR."login/",
                            "PATH_TO_PROFILE" => SITE_DIR."personal/"
                        ),
                        false,
                        array()
                    );?>

                </div>
            </div>
            <? $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "header_menu_wide",
                array(
                    "ALLOW_MULTI_SELECT" => "N",
                    "CHILD_MENU_TYPE" => "left",
                    "DELAY" => "N",
                    "MAX_LEVEL" => "1",
                    "MENU_CACHE_GET_VARS" => array(),
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "ROOT_MENU_TYPE" => "top_wide",
                    "USE_EXT" => "N",
                    "COMPONENT_TEMPLATE" => "header_menu_wide"
                ),
                false
            ); ?>
        </div>
    </div>
</header>

<main class="main <?= $mainPage ? "main_home-page" : "" ?>">
    <? if (!$mainPage) { ?>
        <ul class="breadcrumbs">
            <li>
                <a href="index.html">Главная</a>
            </li>
            <li>
                <a href="catalog.html">Шины</a>
            </li>
            <li>
                Шины зимние
            </li>
        </ul>
    <? } ?>
