<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>
<div class="menu-user">
    <ul class="user-nav">
        <li class="user-nav__item"><a href="#" class="favourite"></a></li>
        <li class="user-nav__item"><a href="<?=$arParams['PATH_TO_PROFILE']?>" class="profile"></a></li>
        <li class="user-nav__item list-item-basket"><a href="<?=$arParams['PATH_TO_BASKET']?>" class="basket"></a>
            <div class="basket-count"><?=$arResult['BASKET_COUNT_DESCRIPTION']?></div>
        </li>
    </ul>
</div>