/*Lazy-load*/
BX.ready(function () {
    document.addEventListener("DOMContentLoaded", function () {
        var lazyloadImages, lazyloadBackground;
        //let styleMainSlider = "center center no-repeat; background-size:cover; width: 100%; height: 100%;";

        if ("IntersectionObserver" in window) {
            lazyloadImages = document.querySelectorAll(".mylazy");
            lazyloadBackground = document.querySelectorAll(".background-lazy");
            var imageObserver = new IntersectionObserver(function (entries, observer) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting || entry.target.classList.contains("hard-lazy")) {
                        var image = entry.target;
                        image.src = image.dataset.src;
                        image.classList.remove("mylazy");
                        imageObserver.unobserve(image);
                    }
                });
            });

            var imageObserverBackground = new IntersectionObserver(function (entries, observer) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        var image = entry.target;
                        image.style = "background-image:url(" + image.dataset.src + ") "/* + styleMainSlider*/;
                        image.classList.remove("background-lazy");
                        imageObserverBackground.unobserve(image);
                    }
                });
            });

            lazyloadImages.forEach(function (image) {
                imageObserver.observe(image);
            });
            lazyloadBackground.forEach(function (image) {
                imageObserverBackground.observe(image);
            });
        } else {
            var lazyloadThrottleTimeout;
            lazyloadImages = document.querySelectorAll(".mylazy");
            lazyloadBackground = document.querySelectorAll(".background-lazy");

            function lazyload() {
                if (lazyloadThrottleTimeout) {
                    clearTimeout(lazyloadThrottleTimeout);
                }

                lazyloadThrottleTimeout = setTimeout(function () {
                    var scrollTop = window.pageYOffset;
                    lazyloadImages.forEach(function (img) {
                        if (img.offsetTop < (window.innerHeight + scrollTop)) {
                            img.src = img.dataset.src;
                            img.classList.remove('mylazy');
                        }
                    });
                    lazyloadBackground.forEach(function (img) {
                        if (img.offsetTop < (window.innerHeight + scrollTop)) {
                            img.style = "background-image:url(" + img.dataset.src + ") "/* + styleMainSlider*/;
                            img.classList.remove('background-lazy');
                        }
                    });
                    if (lazyloadImages.length == 0 && lazyloadBackground.length == 0) {
                        document.removeEventListener("scroll", lazyload);
                        window.removeEventListener("resize", lazyload);
                        window.removeEventListener("orientationChange", lazyload);
                    }
                }, 20);
            }

            // window.addEventListener("onload", lazyload);
            document.addEventListener("scroll", lazyload);
            window.addEventListener("resize", lazyload);
            window.addEventListener("orientationChange", lazyload);
        }
    });
});

/*
HTML:
src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-
*/
