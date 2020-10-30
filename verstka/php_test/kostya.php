<?php
?>

    <input id="userID" type="hidden" value="124">
    <label>ID задачи: </label><input type="number" min="0" value="" id="taskID" placeholder="Введите ID задачи">
    <br>
    <br>
    <label><b>Трудозатраты</b></label><br>
    <label>Часы: </label><input type="number" id="hours" value="0" min="0" placeholder="">
    <label>Минуты: </label><input type="number" id="minutes" value="0" min="0" placeholder="">
    <label>Секунды: </label><input type="number" id="seconds" value="0" min="0" placeholder="">
    <br>
    <br>
    <label><b>Комметарий</b></label><br>
    <textarea cols="100" id="comment"></textarea>
    <br><br>
    <button onclick="send()">Добавить</button>
    <br><br>
    <label id="result"></label>

<script ENGINE="text/javascript" src="https://code.jquery.com/jquery-1.11.2.js "></script>
<script>
    function send() {
        document.getElementById("result").textContent = "";

        let param = {};
        param['userID'] = document.getElementById("userID").value;
        param['taskID'] = document.getElementById("taskID").value;
        param['hours'] = document.getElementById("hours").value;
        param['minutes'] = document.getElementById("minutes").value;
        param['seconds'] = document.getElementById("seconds").value;
        param['comment'] = document.getElementById("comment").value;

        console.log(param);

        $.ajax({
            url: "/php_test/test.php",
            type: 'POST',
            dataType: 'json',
            data: {myData: param},
            success: function (data) {
                if (data["result"]) {
                    console.log(data["result"]);
                    document.getElementById("result").textContent = data["result"];
                } else {
                    console.log('Ошибка при отправке cURL.');
                    document.getElementById("result").textContent = 'Ошибка при отправке cURL.';
                }
            }
        });
    }
</script>