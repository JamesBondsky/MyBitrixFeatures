<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader,
    Bitrix\Iblock;

class MyNewsComponent extends CBitrixComponent
{
    function onPrepareComponentParams($params)
    {
        if ($params['CACHE_TYPE'] == 'Y' || $params['CACHE_TYPE'] == 'A') {
            $params['CACHE_TIME'] = intval($params['CACHE_TIME']);
        } else {
            $params['CACHE_TIME'] = 0;
        }
        return $params;
    }

    public function executeComponent()
    {
        try {
            if ($this->startResultCache(false)) {
                $this->checkModules();

                $this->includeComponentTemplate();
            }
        } catch (Exception $e) {
            $this->AbortResultCache();
            $this->arResult['ERROR'] = $e->getMessage();
        }
    }

    protected function checkModules()
    {
        #подключаем нужные модули
        if (!Loader::includeModule('iblock'))
            throw new Exception('Модуль "Инфоблоки" не установлен');
    }
}
