//добавляет маску для ввода телефонов
BX.ready(function () {
    window.addEventListener("DOMContentLoaded", function () {
        function setCursorPosition(pos, elem) {
            elem.focus();
            if (elem.setSelectionRange) elem.setSelectionRange(pos, pos);
            else if (elem.createTextRange) {
                var range = elem.createTextRange();
                range.collapse(true);
                range.moveEnd("character", pos);
                range.moveStart("character", pos);
                range.select()
            }
        }

        function mask(event) {
            let matrix = "+7 (___) ___ __-__",
                i = 0,
                def = matrix.replace(/\D/g, ""),
                val = this.value.replace(/\D/g, "");
            if (def.length >= val.length) val = def;
            this.value = matrix.replace(/./g, function (a) {
                return /[_\d]/.test(a) && i < val.length ? val.charAt(i++) : i >= val.length ? "" : a
            });
            if (event.type == "blur") {
                if (this.value.length == 2) this.value = ""
            } else setCursorPosition(this.value.length, this)
        }

        let tels = $("input[type=tel]");
        for (let i = 0; i < tels.length; i++) {
            let tel = tels[i];
            tel.addEventListener("input", mask, false);
            tel.addEventListener("change", mask, false);
            tel.addEventListener("focus", mask, false);
            tel.addEventListener("blur", mask, false);
        }
    });
});