$(document).ready(function () {
    $(".product__rating-star").click(function () {
        $(".product__rating-star--active").removeClass("product__rating-star--active");
        $(this).addClass("product__rating-star--active");
    });
});