<?php
require_once 'db.php';
global $mysqli;
$token = $_GET["token"] ?? null;
if (isset($token)) {
    $users = $mysqli->query("SELECT * FROM users WHERE token = '$token'");
    if ($users->num_rows > 0) {
        $usersArray = $users->fetch_assoc();
        unset($usersArray["password"]);
        unset($usersArray["token"]);
        echo json_encode($usersArray,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(array("error"=>array("error_code"=>11,"error_massage"=>"Не верный токен!")),JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(array("error"=>array("error_code"=>12,"error_massage"=>"Не передан токен!")),JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}