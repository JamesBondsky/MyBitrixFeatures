<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?
pre($arResult);
?>
<div class="slick_slider_goods slider-good">
    <article class="slider-good-item">
        <div class="card-header">
            <div class="card-hit card-hit-action">
                Акция
            </div>
            <div class="price">
                <span class="new_price">200<span class="currency">&nbsp;₽</span></span>
                <span class="old_price">500<span class="currency">&nbsp;₽</span></span>
            </div>
        </div>

        <div class="card">
            <img src="src/lamp.png">
            <span class="good-name">
                            Лампочка mini...
                        </span>
        </div>
        <div class="card-footer">
            <div class="text-remaning">До конца акции осталось:</div>
            <div class="card-footer-timer">
                <i class="countdown_icon"></i>
                <div class="countdown__dial hours">
                    <span class="numbers">00</span>
                    <span class="captions">часов</span>
                </div>
                <div class="countdown__dial minutes">
                    <span class="numbers">20</span>
                    <span class="captions">минут</span>
                </div>
                <div class="countdown__dial seconds">
                    <span class="numbers">00</span>
                    <span class="captions">секунд</span>
                </div>
            </div>
        </div>
        <a href="#" class="good-detail_link">
            <span class="visually-hidden">Перейти на страницу товара</span>
        </a>
    </article>
    <article class="slider-good-item">
        <div class="card-header">
            <div class="card-hit card-hit-action">
                Акция
            </div>
            <div class="price">
                <span class="new_price">200<span class="currency">&nbsp;₽</span></span>
                <span class="old_price">500<span class="currency">&nbsp;₽</span></span>
            </div>

        </div>

        <div class="card">
            <img src="src/lamp.png">
            <span class="good-name">
                            Лампочка mini...
                        </span>
        </div>
        <div class="card-footer">
            <div class="text-remaning">До конца акции осталось:</div>
            <div class="card-footer-timer">
                <i class="countdown_icon"></i>
                <div class="countdown__dial hours">
                    <span class="numbers">00</span>
                    <span class="captions">часов</span>
                </div>
                <div class="countdown__dial minutes">
                    <span class="numbers">20</span>
                    <span class="captions">минут</span>
                </div>
                <div class="countdown__dial seconds">
                    <span class="numbers">00</span>
                    <span class="captions">секунд</span>
                </div>
            </div>
        </div>
        <a href="#" class="good-detail_link">
            <span class="visually-hidden">Перейти на страницу товара</span>
        </a>
    </article>
</div>
<!--<div class="orbit" role="region" aria-label="Favorite Space Pictures" data-orbit>-->
<!--    <div class="orbit-wrapper">-->
<!--        <div class="orbit-controls">-->
<!--            <button class="orbit-previous"><span class="show-for-sr">Предыдущий</span>&#9664;&#xFE0E;</button>-->
<!--            <button class="orbit-next"><span class="show-for-sr">Следующий</span>&#9654;&#xFE0E;</button>-->
<!--        </div>-->
<!--        <ul class="orbit-container">-->
<!---->
<!--            --><?//foreach ($arResult as $item): ?>
<!---->
<!--                --><?//
//                $file = CFile::ResizeImageGet($item["PREVIEW_PICTURE"], array('width' => 1100,'height' => 500), BX_RESIZE_IMAGE_EXACT, true);
//                ?>
<!---->
<!--                <li class="orbit-slide">-->
<!--                    <figure class="orbit-figure">-->
<!--                        <img class="orbit-image" src="--><?//=$file['src']?><!--" alt="Space">-->
<!--                        <figcaption class="orbit-caption">--><?//=$item['NAME']?><!--</figcaption>-->
<!--                    </figure>-->
<!--                </li>-->
<!---->
<!--            --><?//endforeach;?>
<!---->
<!--        </ul>-->
<!--    </div>-->
<!---->
<!--    <nav class="orbit-bullets">-->
<!---->
<!--        --><?//foreach ($arResult as $item=>$key): ?>
<!--            <button data-slide="--><?//=$key?><!--"><span class="show-for-sr">--><?//=$item['NAME']?><!--</span></button>-->
<!--        --><?//endforeach;?>
<!---->
<!--    </nav>-->
<!--</div>-->