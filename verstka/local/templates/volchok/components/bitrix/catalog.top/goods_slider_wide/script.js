$(document).ready(function () {
    $('.slick_slider_goods-wide').slick({
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

    // $('.good-basket__link-buy').on('click', function(){
    //     var $this = $(this);
    //     var prodId = parseInt($this.data('prod-id'));
    //     var cartAddUrl = $this.data('url');
    //     var $item = $this.closest('.jsItem');
    //     // var cnt = $item.find('.jsIncrementer .counter_input').val();
    //     var cnt = 1;
    //
    //     console.log('prodId', prodId);
    //
    //     $.ajax({
    //         url:cartAddUrl,
    //         method:'post',
    //         dataType: 'json',
    //         data: {
    //             'cnt': cnt,
    //             'prod-id': prodId,
    //         },
    //         success: function(obj) {
    //             if (obj.hasError) {
    //                 alert('Ошибка добавления товара в корзину');
    //                 return false;
    //             }
    //
    //             $this.html('в корзине').addClass('active');
    //             console.log(obj);
    //         },
    //         error: function(p1, p2, p3) {
    //             console.log(p1, p2, p3);
    //         }
    //     });
    //
    //     return false;
    // });
});

// var ajax = $.ajax({
//     type: 'POST',
//     url: location.pathname + '?action=ADD2BASKET&id=105',
//     data: {
//         ajax_basket: 'Y',
//         quantity: '1'
//     }
// });
//
// ajax.done(function(data) {
//     if (data.STATUS == 'OK') {
//         // Товар успешно добавлен
//     }
// });
