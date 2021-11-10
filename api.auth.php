<?php
require_once 'db.php';
global $mysqli;
$type = $_GET["type"] ?? null;
if (isset($type)) {
    $login = $_GET["login"] ?? null;
    $password = $_GET["password"] ?? null;
    if ($type == "SignIn" or $type == "SignUp") {
        if ($type == "SignIn") {
            if (isset($login) and isset($password)) {
                $users = $mysqli->query("SELECT * FROM users WHERE login = '$login' AND password = '$password'");
                if ($users->num_rows > 0) {
                    $mysqli->query("UPDATE users SET  token = '" . md5(time()) . "' WHERE id = " . $users->fetch_assoc()["id"]);
                    $usersView = $mysqli->query("SELECT * FROM users WHERE login = '$login' AND password = '$password'");
                    echo json_encode(array("token" => $usersView->fetch_assoc()["token"]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(array("error" => array("error_code" => 1, "error_massage" => "Не правильный логин или пароль!")), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                }
            } else {
                echo json_encode(array("error" => array("error_code" => 2, "error_massage" => "Не передан логин или пароль!")), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
        }
        if ($type == "SignUp") {
            $name = $_GET["name"] ?? null;
            $surname = $_GET["surname"] ?? null;
            if (isset($login) and isset($password) and isset($name) and isset($surname)) {
                if ($mysqli->query("SELECT * FROM users WHERE login = '$login'")->num_rows <= 0) {
                    $token = md5(time());
                    if ($mysqli->query("INSERT INTO users (name, surname, login, password, token, time) VALUES ('$name','$surname','$login','$password','$token','" . time() . "')")) {
                        echo json_encode(array("token" => $token), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    } else {
                        echo json_encode(array("error" => array("error_code" => 3, "error_massage" => "Регистрация не удалась, попробуйте позже!")), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    }
                } else {
                    echo json_encode(array("error" => array("error_code" => 4, "error_massage" => "Пользователь с таким логином уже существует!")), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                }
            } else {
                echo json_encode(array("error" => array("error_code" => 5, "error_massage" => "Заполните все поля для регистрации!")), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
        }
    } else {
        echo json_encode(array("error" => array("error_code" => 6, "error_massage" => "Не передан один из двух типо авторизации: SignIn и SignUp!")), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(array("error" => array("error_code" => 7, "error_massage" => "Не передан один из двух типо авторизации: SignIn и SignUp!")), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}