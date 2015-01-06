<?php

include '/../library/CommonClasses.php';
require_once '/../library/Session.php';

if (!$guard->isUserAuthenticated()) {
    header("Location: " . $baseUrl);
    die();
}

Session::regenerateId(true);
Session::destroy();

header("Location: " . $baseUrl);
die();
