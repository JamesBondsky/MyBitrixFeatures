<? //require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
//pre('start');

#-------------ТУТ ВАШ КОД
#Что нужно сделать: Загрузить XML в PHP (рекомендую simplexml_load_file). Далее вывести на экран каждый элемент со свойствами
#Цель: Аккуратно окунуть Вас в работу с самим PHP и посмотреть у кого возникнут сложности с чистым PHP. Далее мы имея данные в массивах/обьектах научимся загружать это непосредственно в Bitrix.
#-------------КОНЕЦ КОДА

if (file_exists('../integration/data/data.xml')) {
    $xml = simplexml_load_file('../integration/data/data.xml');
    printer($xml);
    //print_r($xml);
} else {
    exit('Не удалось открыть файл.');
}

 function printer($obj)
    {
        foreach ($obj as $product) {
            echo "______________________________________________________________________________________________________\n";
            foreach ($product as $field) {
                echo $field->getName() . ": ";
                if ($field->count() == 0) {
                    echo $field . "\n";
                } else {
                    echo ("\n");
                    printer2($field);
                }
            }
        }
    }

function printer2($field) {
    foreach ($field as $child) {
        echo $child->getName().": ";
        if ($child->count() == 0) {
            echo $child . "\n";
        } else {
            echo("\n");
            printer2($child);
        }
    }
}

//pre('done.');