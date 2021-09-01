<?php header("Content-Type: text/html; charset=utf-8");

require_once("model/Database.php");
require_once("model/File.php");
require_once("model/User.php");
require_once("config/settings.php");
require_once("functions.php");

session_start();

if(isset($_SESSION["uid"])){
    define("HOME", realpath(STORAGE . DIRECTORY_SEPARATOR . $_SESSION["uid"]));
}

?>