function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function closeWindow() {
    ym('9945598', 'reachGoal', 'HIDE_WEATHER_WINDOW');
    document.getElementById('weatherWindow').hidden = true;
    document.getElementById('weatherClose').hidden = false;
    sendAjaxSession(1);
}

function openWindow() {
    document.getElementById('weatherWindow').hidden = false;
    document.getElementById('weatherClose').hidden = true;
    sendAjaxSession(0);
}

function sendAjaxSession(value) {
    $.ajax({
        url: "/local/php_interface/progress/ajax/setSession.php",
        type: 'POST',
        dataType: 'json',
        data: {field: 'hidden', value: value},
    });
}