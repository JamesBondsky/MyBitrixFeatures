/*
вызов данного метода происходит в /bitrix/components/aspro/form.allcorp2/templates/popup/template.php
submitHandler: function( form ){
				if( $('.popup form[name="<?=$arResult["IBLOCK_CODE"]?>"]').valid() ){
					...........
                    sendLid(form);
				}
            },
также в начале того файла надо добавить <?$this->addExternalJs("/local/js/leads.js");?>
с подключением данного файла
*/
function sendLid(formBody) {
    try {
        let fields = formBody.getElementsByClassName('form-control');
        let titleForm = formBody.getElementsByClassName('title')[0].textContent;
        let paramStr = '';
        let param = {};
        for (let i = 0; i < fields.length; i++) {
            let field = fields[i];
            let fName = field.getAttribute("name");
            let fVal = field.value;
            param[fName] = fVal;
            paramStr += fName + ': ' + fVal + ' | ';
        }

        writeLog('Ajax сформирован. Форма: ' + titleForm + '; Данные: ' + paramStr);

        $.ajax({
            url: "/local/php_interface/progress/ajax/ajaxLeads.php",
            type: 'POST',
            dataType: 'json',
            data: {myData: param, titleForm: titleForm},
            success: function(data) {
                if(data["result"]) {
                    writeLog(data["result"]);
                } else {
                    writeLog('Ошибка при отправке cURL.')
                }
            }
        });
    } catch (e) {
        writeLog('Ошибка отправки Ajax.');
    }
}

function writeLog(str) {
    $.ajax(
        {
            type: 'POST',
            url: '/local/php_interface/progress/logs/logs.php',
            data: {log: str},
        }
    );
}