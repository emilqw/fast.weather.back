<?php
require_once 'db.php';
require_once "API/API.php";
global $mysqli;
$token = $_GET["token"] ?? null;
$lat = $_GET["lat"] ?? null;
$lon = $_GET["lon"] ?? null;
$limit = $_GET["limit"] ?? null;
$hours = $_GET["hours"] ?? null;
$extra = $_GET["extra"] ?? null;
$lang = $_GET["lang"] ?? null;
$limit = isset($limit)? $limit:7;
$hours = $hours != null? is_bool($hours):true;
$extra = isset($extra)? is_bool($hours):false ;
$lang = isset($lang)? $lang:"ru_RU";
if (isset($token)) {
    $users = $mysqli->query("SELECT * FROM users WHERE token = '$token'");
    if ($users->num_rows > 0) {
        if (isset($lat) and  isset($lon)) {
            $weather = API::get($lat, $lon, $limit, $hours, $extra, $lang);
            unset($weather["now"], $weather["now_dt"], $weather["info"]);
            echo json_encode($weather, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(array("error" => array("error_code" => 21, "error_massage" => "Не переданы обязательные параметры lat и lon!")),JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode(array("error" => array("error_code" => 11, "error_massage" => "Не верный токен!")),JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(array("error" => array("error_code" => 12, "error_massage" => "Не передан обязательный параметр token!")),JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}