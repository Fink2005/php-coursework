<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';
require_once "./mvc/Bridge.php";
$myApp = new App();
?>