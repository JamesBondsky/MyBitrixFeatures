<?
$arDisplays = array("block", "list");
$arDisplaysNames = array("block" => "плиткой", "list" => "списком");

if (array_key_exists("display", $_REQUEST) || (array_key_exists("display", $_SESSION)) || $arParams["DEFAULT_LIST_TEMPLATE"]) {
    if ($_REQUEST["display"] && (in_array(trim($_REQUEST["display"]), $arDisplays))) {
        $display = trim($_REQUEST["display"]);
        $_SESSION["display"] = trim($_REQUEST["display"]);
    } elseif ($_SESSION["display"] && (in_array(trim($_SESSION["display"]), $arDisplays))) {
        $display = $_SESSION["display"];
    } elseif ($arCurSection["DISPLAY"]) {
        $display = $arCurSection["DISPLAY"];
    } else {
        $display = $arParams["DEFAULT_LIST_TEMPLATE"];
    }
} else {
    $display = "block";
}
$listElementsTemplate = "catalog_" . $display;


$sortNames = array("PRICE" => "По цене", "SHOWS" => "По популярности");
$arAvailableSort = array("PRICE" => array("PRICE", "asc"), "SHOWS" => array("SHOWS", "asc"));
$sort = "SHOWS";
if ((array_key_exists("sort", $_REQUEST) && array_key_exists(ToUpper($_REQUEST["sort"]), $arAvailableSort)) || (array_key_exists("sort", $_SESSION) && array_key_exists(ToUpper($_SESSION["sort"]), $arAvailableSort)) || $arParams["ELEMENT_SORT_FIELD"]) {
    if ($_REQUEST["sort"]) {
        $sort = ToUpper($_REQUEST["sort"]);
        $_SESSION["sort"] = ToUpper($_REQUEST["sort"]);
    } elseif ($_SESSION["sort"]) {
        $sort = ToUpper($_SESSION["sort"]);
    } else {
        $sort = ToUpper($arParams["ELEMENT_SORT_FIELD"]);
    }
}
$sort_order = $arAvailableSort[$sort][1];
if ((array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), array("asc", "desc"))) || (array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), array("asc", "desc"))) || $arParams["ELEMENT_SORT_ORDER"]) {
    if ($_REQUEST["order"]) {
        $sort_order = $_REQUEST["order"];
        $_SESSION["order"] = $_REQUEST["order"];
    } elseif ($_SESSION["order"]) {
        $sort_order = $_SESSION["order"];
    } else {
        $sort_order = ToLower($arParams["ELEMENT_SORT_ORDER"]);
    }
}
//pre($sort);
//pre($sort_order);

$sortName = $sortNames[$sort];

if ($sort == "PRICE") {
    $price_name = ($arParams["SORT_REGION_PRICE"] ? $arParams["SORT_REGION_PRICE"] : "BASE");
    $price = CCatalogGroup::GetList(array(), array("NAME" => $price_name), false, false, array("ID", "NAME"))->GetNext();
    $sort = $price["ID"];
}

if ($sort == "CATALOG_AVAILABLE") {
    $sort = "CATALOG_QUANTITY";
}
?>


<div class="sorting_wrap">
    <div class="sorting">
        <button type="button" class="sorting__custom-select">
            <?= $sortName ?>
        </button>
        <ul class="sorting__list">
            <? foreach ($arAvailableSort as $availableSort) { ?>
                <li>
                    <a href="<?= $APPLICATION->GetCurPageParam('sort=' . $availableSort[0] . '&order=' . $availableSort[1], array('sort', 'order')); ?>"><?= $sortNames[$availableSort[0]] ?></a>
                </li>
            <? } ?>
        </ul>
    </div>
    <div class="top-filter__sorting-mobile">
        <button type="button">
            Фильтры
        </button>
    </div>
</div>
<div class="view-type">
    <? foreach ($arDisplays as $arDisplay) {
        $title = $arDisplaysNames[$arDisplay];
        ?>
        <a class="view-type__button--<?= $arDisplay ?> <?= $display == $arDisplay ? 'view-type__button--selected' : '' ?>"
           href="<?= $APPLICATION->GetCurPageParam('display=' . $arDisplay, array('display')) ?>">
            <i class="visually-hidden" title="<?= $title ?>"><?= $title ?></i>
        </a>
    <? } ?>
</div>
