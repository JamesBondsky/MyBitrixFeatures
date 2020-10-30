$(document).ready(function () {
    var minPrice = document.getElementById('min-price-mask');
    var maxPrice = document.getElementById('max-price-mask');
    var previousMinPriceValue;
    var previousMaxPriceValue;

    function parsePrice(inputValue) {
        return parseInt(inputValue.replace(/ /g, ''), 10);
    }

    if (minPrice) {
        minPrice.addEventListener('focus', function (evt) {
            previousMinPriceValue = evt.target.value;
        });

        minPrice.addEventListener('blur', function (evt) {
            var newMinPrice = parsePrice(evt.target.value);
            if (newMinPrice < parsePrice(maxPrice.value)) {
                var newX = newMinPrice / (maxPriceValue - minPriceValue) * scale.clientWidth;
                updateMin(newX);
            } else {
                evt.target.value = previousMinPriceValue;
            }
        });
    }

    if (maxPrice) {
        maxPrice.addEventListener('focus', function (evt) {
            previousMaxPriceValue = evt.target.value;
        });
        maxPrice.addEventListener('blur', function (evt) {
            var newMaxPrice = parsePrice(evt.target.value);
            if (newMaxPrice > parsePrice(minPrice.value)) {
                var newX = newMaxPrice / (maxPriceValue - minPriceValue) * scale.clientWidth;
                updateMax(newX);
            } else {
                evt.target.value = previousMaxPriceValue;
            }
        });
    }

    var sliderContainer = document.querySelector('.left-filter__price--fieldset');
    var sliderMin = document.querySelector('.left-filter__range-toggle--min');
    var sliderMax = document.querySelector('.left-filter__range-toggle--max');
    var scale = document.querySelector('.left-filter__scale');
    var sliderBar = document.querySelector('.left-filter__bar');
    var minPriceValue = 0;
    var maxPriceValue = 30000;
    var minMoving = false;
    var maxMoving = false;

    sliderMin.addEventListener('mousedown', function () {
        minMoving = true;
    });

    sliderMax.addEventListener('mousedown', function () {
        maxMoving = true;
    });

    document.addEventListener('mousemove', function (evt) {
        if (minMoving || maxMoving) {
            var newX = evt.clientX - sliderContainer.offsetLeft;
            var newPrice = Math.floor((maxPriceValue - minPriceValue) * newX / scale.clientWidth);
            if (minMoving && newX >= 0 && newX < sliderMax.offsetLeft) {
                updateMin(newX);
                minPrice.value = newPrice;
            }
            if (maxMoving && newX >= sliderMin.offsetLeft && newX <= scale.clientWidth) {
                updateMax(newX);
                maxPrice.value = newPrice;
            }
        }
    });

    document.addEventListener('mouseup', function (evt) {
        minMoving = false;
        maxMoving = false;
        // if (!evt.target.closest('.custom-select')) {
        //     closeAllSelects();
        // }
    });

    function updateMin(newX) {
        sliderMin.style.left = newX + 'px';
        sliderBar.style.marginLeft = sliderMin.style.left;
        sliderBar.style.width = (sliderMax.offsetLeft - newX) + 'px';
    }

    function updateMax(newX) {
        sliderMax.style.left = newX + 'px';
        sliderBar.style.width = (newX - sliderMin.offsetLeft) + 'px';
    }
});