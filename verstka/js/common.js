$(document).ready(function () {
    initTabs(".search__tabs");
    initTabs(".popular__tabs");
    initTabs(".suggestion__tabs");
    initTabs(".product__description.tabs");
    initTabs(".product__info.tabs");

    $("button.favourite").click(function () {
        $(this).toggleClass("favourite-active");
    });

    $('.slick_slider_images').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        responsive: [
            //     {
            //     breakpoint: 1232,
            //     settings: {
            //         slidesToShow: 3,
            //         slidesToScroll: 3,
            //         // arrows: false
            //     }
            // },
            // {
            //     breakpoint: 1024,
            //     settings: {
            //         arrows: false,
            //         dots: true,
            //         slidesToShow: 2,
            //         slidesToScroll: 2
            //     }
            // },
            {
                breakpoint: 768,
                settings: {
                    arrows: false,
                    dots: true,
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    $('.slick_slider_goods').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        arrows: false
    });


    // $('.popular-slick_slider').slick({
    //     slidesToShow: 4,
    //     slidesToScroll: 4,
    //     dots: false,
    //     arrows: true,
    //     responsive: [{
    //         breakpoint: 1232,
    //         settings: {
    //             slidesToShow: 3,
    //             slidesToScroll: 3,
    //             // arrows: false
    //         }
    //     }, {
    //         breakpoint: 1024,
    //         settings: {
    //             arrows: false,
    //             dots: true,
    //             slidesToShow: 2,
    //             slidesToScroll: 2
    //         }
    //     }, {
    //         breakpoint: 768,
    //         settings: {
    //             arrows: false,
    //             dots: true,
    //             slidesToShow: 1,
    //             slidesToScroll: 1
    //         }
    //     }]
    // });


    $('.slick_slider').slick({
        slidesToShow: 4,
        slidesToScroll: 4,
        dots: false,
        arrows: true,
        responsive: [{
            breakpoint: 1232,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                arrows: false
            }
        }, {
            breakpoint: 1024,
            settings: {
                arrows: false,
                dots: true,
                slidesToShow: 2,
                slidesToScroll: 2
            }
        }, {
            breakpoint: 768,
            settings: {
                arrows: false,
                dots: true,
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }]
    });

    $(".mobile-menu__toogle").click(function () {
        // if ($(this).hasClass("mobile-menu__toogle--active")) {
        $(this).toggleClass("mobile-menu__toogle--active");
        $("body").toggleClass("menu-opened");
        // } else {
        //     $(this).addClass("mobile-menu__toogle--active");
        // $("body").addClass("menu-opened");
        // }
    });

    $(".footer-section button").click(function () {
        $(this).parent().toggleClass("footer-section--opened");
    });

    $(".sorting__custom-select").click(function () {
        $(this).parent().toggleClass("sorting--opened");
    });

    $(".view-type a").click(function () {
        $('.view-type__button--selected').removeClass("view-type__button--selected");
        $(this).addClass("view-type__button--selected");
    });

    $(".hide-button").click(function () {
       $(this).parent().toggleClass("param--active");
    });

    $(".top-filter__sorting-mobile button").click(function () {
        $("body").toggleClass("filter-opened");
    });
});

function initTabs(classTabs) {
    $(classTabs + " .tabs__link").click(function () {
        if ($(this).hasClass("spoiler")) {
            // $(this).toggleClass("tabs__link--active");
            // $(classTabs + " .tabs__link--active").toggleClass("tabs__link--active");
            // $(classTabs + " .tabs__panel").toggleClass("tabs__panel--active");
            $($(this).attr("href")).toggleClass("tabs__panel--active");
        } else {
            $(classTabs + " .tabs__link--active").removeClass("tabs__link--active");
            $(this).addClass("tabs__link--active");
            $(classTabs + " .tabs__panel").removeClass("tabs__panel--active");
            $($(this).attr("href")).addClass("tabs__panel--active");
        }
        return false;
    });
}


