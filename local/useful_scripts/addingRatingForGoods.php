<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/me/iBlocksAndOther/iBlockWorking.php");

if (CModule::IncludeModule("iblock")) {

    $els = getListOfElementsWithPropertiesAsArray(25);
    foreach ($els as $id => $el) {
        $propusk = rand(0, 10);
        pre('Propusk: ' . $propusk);
        if ($propusk > 2) {
            $rating = ($el['PROPS']['rating']['VALUE'] ? $el['PROPS']['rating']['VALUE'] : 0);
            $vote_sum = ($el['PROPS']['vote_sum']['VALUE'] ? $el['PROPS']['vote_sum']['VALUE'] : 0);
            $vote_count = ($el['PROPS']['vote_count']['VALUE'] ? $el['PROPS']['vote_count']['VALUE'] : 0);
            pre($rating);
            pre($vote_sum);
            pre($vote_count);

            $countOc = rand(1, 2);
            pre("count: " . $countOc);

            for ($ii = 1; $ii <= $countOc; $ii++) {
                $r = rand(0, 100);
                if ($r >= 70) {
                    $oc = 4;
                } else $oc = 5;
                pre('OC: ' . $oc);
                $vote_sum += $oc;
                $vote_count++;
            }
            $rating = round(($vote_sum + 31.25) / ($vote_count + 10), 2);
            pre($rating);

            setPropertyValue(25, $id, "rating", $rating);
            setPropertyValue(25, $id, "vote_sum", $vote_sum);
            setPropertyValue(25, $id, "vote_count", $vote_count);
        }
    }
    //foreach ($els as $id => $el) {
    //pre($el['PROPERTIES']['rating']);
//        $sum = 0;
//        $count = 0;
//        for ($ii = 1; $ii <= 10; $ii++) {
//            $r = rand(0, 100);
//            if ($r >= 97) {
//                $oc = 3;
//            } else {
//                if ($r >= 73) {
//                    $oc = 4;
//                } else
//                    $oc = 5;
//            }
//            //pre($oc);
//            $sum += $oc;
//            $count++;
//            $rating = round(($sum + 31.25) / ($count + 10), 2);
//            pre($rating);
//        }
//        setPropertyValue(25, $id, "rating", $rating);
//        setPropertyValue(25, $id, "vote_sum", $sum);
//        setPropertyValue(25, $id, "vote_count", $count);
//        pre($rating);
    //}
}
