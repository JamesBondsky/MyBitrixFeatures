<?php
//данный код лишь пример
//вызывает перед вызовом компонента каталога в catalog/index.php

//т.е. существует инфоблок (ID=27), в котором есть свойство PROPERTY_ORIGINAL_URL, содержащее реальную страницу,
//и свойство PROPERTY_PAGE, в которое записывается URL, по которому она будет открываться
$seo = CIBlockElement::GetList(array(), array("IBLOCK_ID" => 27, "PROPERTY_PAGE" => explode("?", $_SERVER["REQUEST_URI"])[0]), false, false, array("ID", "NAME", "PROPERTY_ORIGINAL_URL"))->fetch();

if ($seo["PROPERTY_ORIGINAL_URL_VALUE"]) {
    $application = \Bitrix\Main\Application::getInstance();
    $context = $application->getContext();
    $request = $context->getRequest();
    $Response = $context->getResponse();
    $Server = $context->getServer();
    $server_get = $Server->toArray();
    $server_get["REQUEST_URI"] = $seo["PROPERTY_ORIGINAL_URL_VALUE"];//$_SERVER["REQUEST_URI"];
    $Server->set($server_get);
    $context->initialize(new Bitrix\Main\HttpRequest($Server, array(), array(), array(), $_COOKIE), $Response, $Server);
    $_SERVER["REQUEST_URI"] = $seo["PROPERTY_ORIGINAL_URL_VALUE"];
    $APPLICATION->reinitPath();
}