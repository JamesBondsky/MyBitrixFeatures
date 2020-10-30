<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
</main>
<footer class="page-footer">
    <div class="page-footer__wrapper">
        <div class="page-footer__container">
            <div class="footer-subscribe_form">
                <h2>
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => "/include/subscribe_text.php",
                            "EDIT_TEMPLATE" => ""
                        )
                    ); ?>
                </h2>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => "/include/ajax/subscribe.php",
                        "EDIT_TEMPLATE" => ""
                    )
                ); ?>
            </div>
            <div class="footer-sections">
                <div class="footer-section info">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "footer_menu_column",
                        array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "left",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => array(),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "bottom_info",
                            "USE_EXT" => "N",
                            "COMPONENT_TEMPLATE" => "footer_menu_column"
                        ),
                        false
                    ); ?>
                </div>
                <div class="footer-section service">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "footer_menu_column",
                        array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "left",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => array(),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "bottom_service",
                            "USE_EXT" => "N",
                            "COMPONENT_TEMPLATE" => "footer_menu_column"
                        ),
                        false
                    ); ?>
                </div>
                <div class="footer-section store">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "footer_menu_column",
                        array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "left",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => array(),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "bottom_shop",
                            "USE_EXT" => "N",
                            "COMPONENT_TEMPLATE" => "footer_menu_column"
                        ),
                        false
                    ); ?>
                </div>
            </div>
        </div>

        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR . "/include/footer_title.php"
            ),
            false
        ); ?>
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR . "/include/footer_mobile_title.php"
            ),
            false
        ); ?>
    </div>
</footer>
</body>
</html>
