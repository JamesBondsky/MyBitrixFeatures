<? //require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/me/iblockAndOther/iBlockWorking.php");

function sendCurl($urlmain, $option)
{
    $url = $urlmain . '?' . http_build_query($option, 'a', '&');
    $curl = curl_init($url);

//curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
////curl_setopt($curl, CURLOPT_REFERER, "http://www.exemple.com");

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $json_response = curl_exec($curl);
    curl_close($curl);
    $responseObj = json_decode($json_response);
    return $responseObj;
}

function getVideosFromPlaylist(&$videoArray, $playlistID, $part, $maxResults = 5, $pageToken = '')
{
    $option = array(
        'part' => $part,
        'playlistId' => $playlistID,
        'maxResults' => $maxResults,
        'pageToken' => $pageToken,
        'key' => API_KEY_YOUTUBE
    );
    $videoArray = sendCurl('https://www.googleapis.com/youtube/v3/playlistItems', $option);
    //return sendCurl('https://www.googleapis.com/youtube/v3/playlistItems', $option);
}

function getVideosFromPlaylistOnlyCodes(&$arrayForResult, $playlistID, $maxResults = 5)
{
    $videoArrayAllFields = array();
    getVideosFromPlaylist($videoArrayAllFields, $playlistID, 'contentDetails', $maxResults);
    $videos = $videoArrayAllFields->items;
    foreach ($videos as $video) {
        $arrayForResult[$video->contentDetails->videoId] = '';
    }
}

//& означает передачу по ссылке
//$arrayForResult - массив по ссылке, в который будут сложены ID в виде ключей
//возвращает строку с ID через запятую
function getAllVideosFromPlaylist(&$arrayForResult, $playlistID, $part, $maxResults = 50)
{
    $pageToken = '';
    $idsStr = '';
    do {
        $option = array(
            'part' => $part,
            'playlistId' => $playlistID,
            'maxResults' => $maxResults,
            'pageToken' => $pageToken,
            'key' => API_KEY_YOUTUBE,
        );
        $execCurl = sendCurl('https://www.googleapis.com/youtube/v3/playlistItems', $option);
        $videos = $execCurl->items;
        foreach ($videos as $video) {
            $arrayForResult[$video->contentDetails->videoId] = '';
            $idsStr = $idsStr . $video->contentDetails->videoId . ',';
        }
        $pageToken = $execCurl->nextPageToken;
        //break;
    } while ($pageToken);
    return $idsStr;
}

//& означает передачу по ссылке
//передаем массив с заполненными ID в виде ключей и строку с ID через запятую
//и то, и другое можно получить в методе getAllVideosFromPlaylist()
function getCountViewsForVideosArray(&$videoArray, $idsStr)
{
    $option = array(
        'part' => 'statistics',
        'id' => $idsStr,
        'key' => API_KEY_YOUTUBE,
    );
    $execCurl = sendCurl('https://www.googleapis.com/youtube/v3/videos', $option);
    $videos = $execCurl->items;
    foreach ($videos as $video) {
        $videoArray[$video->id] = $video->statistics->viewCount;
    }
    //pre($videos);
}

function getSortedVideosByViews($playlistId, $notNeededVideos = false, $count = 0)
{
    $videoArray = array();
    $idsStr = getAllVideosFromPlaylist($videoArray, $playlistId, 'contentDetails');
    getCountViewsForVideosArray($videoArray, $idsStr);
    arsort($videoArray);
    $videoArrayResult = array();
    if (!$notNeededVideos) {
        if ($count != 0)
            $videoArrayResult = array_slice($videoArray, 0, $count);
        else
            $videoArrayResult = $videoArray;
    } else {
        if (!$count) {
            $count = count($videoArray);
        }
            $i = 0;
            foreach ($videoArray as $code => $views) {
                if (!array_key_exists($code, $notNeededVideos)) {
                    if ($i < $count) {
                        $videoArrayResult[$code] = $views;
                        $i++;
                    } else
                        break;
                }
            }
    }

    return $videoArrayResult;
}


function addVideosArrayToIblockElement($iblockID, $elementID, $videoArray)
{
    setPropertyValue($iblockID, $elementID, 'VIDEO', $videoArray, true);
}

function agentFunctionAddVideoToIblock()
{
    $videoArray = array();
    //получаем указанное количество последних видосов
    getVideosFromPlaylistOnlyCodes($videoArray, 'UUkwQ42Q9y04SvcWapDcdazQ', 3);
    //получаем ОСТАЛЬНЫЕ видео в порядке убывания просмотров
    $popularVideos = getSortedVideosByViews('UUkwQ42Q9y04SvcWapDcdazQ', $videoArray);
    //соединяем эти 2 массива
    $fullArray = array_merge($videoArray, $popularVideos);
    //достаем из массива (код видео => количество просмотров) только код видео
    $fullArray = array_keys($fullArray);
    addVideosArrayToIblockElement(44, 906, $fullArray);
    return "agentFunctionAddVideoToIblock();";
}

//agentFunctionAddVideoToIblock();