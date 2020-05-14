<? //require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if(CModule::IncludeModule("iblock")) {
	global $APPLICATION;
	$url = $APPLICATION->GetCurPage(false);
	$iblockID = 3;
		 
	function isSectionInCatalog($iblockID, $url) {
		$breads = explode('/', $url);
		$code = array_pop(array_filter($breads));
		$arFilter = array("IBLOCK_ID" => $iblockID, "CODE" => $code);
		$rsSections = CIBlockSection::GetList(array(), $arFilter);
		$isSection = $rsSections->Fetch() !== false;
		return $isSection;
	}
}