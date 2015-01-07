<?php

include '/../library/CommonClasses.php';
require_once '/../library/Repository/SectionRepository.php';

$sectionId = isset($_GET["sectionId"]) ? (int) $_GET["sectionId"] : 0;
$token = isset($_GET["token"]) ?  $_GET["token"] : "";
$confirm = isset($_GET["confirm"]) ? (int) $_GET["confirm"] : 0;

$sectionRepository = new SectionRepository();
$section = $sectionRepository->findById($sectionId);

if (!$section || !$guard->isAccessGranted("ROLE_DELETE_SECTION") || $token !== AuthUser::getToken()) {
    include "/../library/Layout/PageNotFound.php";
}

if ($confirm === 1)
{
    $sectionRepository->remove($section);
    
    FlashMessage::add("Dział został usunięty");
    header("Location: " . $baseUrl);
    die();
}


include '/../library/Layout/Header.php';
?>

Czy napewno chcesz usunąć dział? <br/><br/>
<a href="<?php echo "section/remove.php?sectionId=" . $section->getId() . "&token=" .AuthUser::getToken() . "&confirm=1" ?>">TAK</a>
<a href="<?php echo "index.php" ?>">ANULUJ</a>



<?php
include '/../library/Layout/Footer.php';
