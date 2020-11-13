<?php
AddEventHandler('form', 'onBeforeResultAdd', 'antiSpamBeforeResultAdd');
function antiSpamBeforeResultAdd($WEB_FORM_ID, &$arFields, &$arrVALUES)
{
    //Нужно создать хайлоадблок с 2 полями:
    //Email (UF_EMAIL) строковый
    //UF_REGULAR да/нет
    $HLB_ID_SPAM_LIST = 5;

    if (\Bitrix\Main\Loader::IncludeModule("highloadblock")) {
        $emails = array();
        foreach ($arrVALUES as $key => $arrVALUE) {
            if (strpos($key, "email") != false)
                $emails[$key] = $arrVALUE;
        }

        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($HLB_ID_SPAM_LIST)->fetch();
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entityDataClass = $entity->getDataClass();

        $arFilter = array(
//        "limit" => 2,
            "select" => array("*"),
//        "order" => array("ID" => "DESC"),
//        "filter" => array("UF_EMAIL" => $emails),
        );

        $rsPropEnums = $entityDataClass::getList($arFilter);
        while ($arEnum = $rsPropEnums->fetch()) {
            foreach ($emails as $email) {
                if ($arEnum["UF_REGULAR"]) {
                    $reg = $arEnum["UF_EMAIL"];
                    if (substr($reg, 0, 1) != "/" && !substr($reg, strlen($reg) - 2, 1) != "/")
                        $reg = '/' . $reg . '/';
                    preg_match($reg, $email, $match);
                    if (!empty($match)) {
                        global $APPLICATION;
                        $APPLICATION->ThrowException('Уходи бот!');
                    }
                } else {
                    if ($email == $arEnum["UF_EMAIL"]) {
                        global $APPLICATION;
                        $APPLICATION->ThrowException('Уходи бот!');
                    }
                }
            }
        }
    }
}
