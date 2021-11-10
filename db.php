<?php
$mysqli = new mysqli("localhost", "root", "rootroot", "weather");
if ($mysqli->connect_errno) {
    exit();
}