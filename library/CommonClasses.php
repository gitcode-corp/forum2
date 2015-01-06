<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

require_once 'Guard.php';
require_once 'AuthUser.php';
require_once 'FlashMessage.php';
require_once 'Session.php';

Session::start();

$guard = new Guard();
$baseUrl = "/forum2/";
$menu = "";
?>