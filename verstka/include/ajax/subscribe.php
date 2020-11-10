<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("subscribe");
// если есть post запрос с почтой то исполняем код
if ($_POST["email"]) {
    $EMAIL = $_POST["email"];
    if (check_email($EMAIL)) {
        /* получим значение пользователя */
        if ($USER->IsAuthorized()) {
            global $USER;
            $USER = $USER->GetID();
        } else {
            $USER = NULL;
        }
        /* определим рубрики активные рубрики подписок */
        $RUB_ID = array();
        $rub = CRubric::GetList(array(), array("ACTIVE" => "Y"));
        while ($rub->ExtractFields("r_")):
            $RUB_ID = array($r_ID);
        endwhile;

        /* создадим массив на подписку */
        $subscr = new CSubscription;
        $arFields = array(
            "USER_ID" => $USER,
            "FORMAT" => "html/text",
            "EMAIL" => $EMAIL,
            "ACTIVE" => "Y",
            "RUB_ID" => $RUB_ID,
            "SEND_CONFIRM" => "Y"
        );
        $idsubrscr = $subscr->Add($arFields);

        if ($idsubrscr) {
            $popuptitle = '<span style="color: green">Удачно</span>';
            $popuptext = $EMAIL . ' подписан на рассылку';
        } else {
            $popuptitle = '<span style="color: red">Ошибка</span>';
            $popuptext = $EMAIL . ' уже подписан на рассылку';
        }
        /* если ajax не подключен */
//    if ($_POST["action"] != "ajax") {
//        header('Location: ' . $_SERVER['HTTP_REFERER']);
//    }
    } else {
        $popuptitle = '<span style="color: red">Ошибка</span>';
        $popuptext = $EMAIL . ' - некорректный Email';
    }

    echo $popuptitle . "<br>" . $popuptext;
    return;
} else
    $buttonId = $this->randString();

?>

<form id="subscr-form_<?= $buttonId ?>" method="post" action="/include/ajax/subscribe.php" name="subscribe">
    <div style="display:none; margin-bottom: 10px;" id="subscr-ans_<?=$buttonId?>"></div>
    <div class="subscribe_form__fields">
        <input class="input-email" name="email" type="email" placeholder="Введите ваш e-mail:" required>
        <button class="button" type="submit">Отправить</button>
    </div>
</form>
<script>
    $('#subscr-form_<?=$buttonId?>').submit(function () {
        $.post(
            '/include/ajax/subscribe.php', // адрес обработчика
            $("#subscr-form_<?=$buttonId?>").serialize(), // отправляемые данные
            function (msg) { // получен ответ сервера
                //$('#subscr-form_<?//=$buttonId?>//').hide('slow');
                $('#subscr-ans_<?=$buttonId?>').html(msg);
                $('#subscr-ans_<?=$buttonId?>').show(500);
                setTimeout(function () {
                    $('#subscr-ans_<?=$buttonId?>').hide(500);
                }, 5000);
            }
        );
        return false;
    });
</script>