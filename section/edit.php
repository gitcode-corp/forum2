<?php

include '/../library/CommonClasses.php';
require_once '/../library/Repository/SectionRepository.php';
require_once '/../library/Validator/SectionValidator.php';

$sectionId = isset($_GET["sectionId"]) ? (int) $_GET["sectionId"] : 0;

$sectionRepository = new SectionRepository();
$section = $sectionRepository->findById($sectionId);

if (!$section || !$guard->isAccessGranted("ROLE_EDIT_SECTION")) {
    include "/../library/Layout/PageNotFound.php";
}

$errors = array(
    "s_name" => array(),
    "s_description" => array(),
    "s_is_closed" => array()
);

$isAdminForm = false;
if ($guard->isAccessGranted("ROLE_CHANGE_SECTION_STATUS")) {
    $isAdminForm = true;
}

if ($_POST) {
    $name = (isset($_POST["s_name"])) ? $_POST['s_name'] : "";
    $description = (isset($_POST["s_description"])) ? $_POST['s_description'] : "";
    $section->setName($name);
    $section->setDescription($description);
    
    $isClosed = false;
    if ($isAdminForm) {
        if (isset($_POST["s_is_closed"]) && intval($_POST['s_is_closed']) === 1) {
            $isClosed = true;
        }
    }

    $section->setIsClosed($isClosed);
    
    $validator = new SectionValidator($section);
    if ($validator->isValid()) {
        $sectionRepository->save($section);
        
        FlashMessage::add("Formularz został zapisany");
        header("Location: " . $baseUrl);
        die();
    } else {
        $errors = array_merge($errors, $validator->getErrors());
    }
}


$messages = FlashMessage::get();

include '/../library/Layout/Header.php';

include '_form.php';

include '/../library/Layout/Footer.php';