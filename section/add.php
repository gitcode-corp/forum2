<?php

include '/../library/CommonClasses.php';
require_once '/../library/Repository/SectionRepository.php';
require_once '/../library/Validator/SectionValidator.php';
require_once '/../library/Entity/Section.php';
require_once '/../library/Entity/User.php';

if (!$guard->isAccessGranted("ROLE_ADD_SECTION")) {
    include "/../library/Layout/PageNotFound.php";
}

$section = new Section();
$errors = array(
    "s_name" => array(),
    "s_description" => array(),
    "s_is_closed" => array()
);



if ($_POST) {
    $name = (isset($_POST["s_name"])) ? $_POST['s_name'] : "";
    $description = (isset($_POST["s_description"])) ? $_POST['s_description'] : "";
    $section->setName($name);
    $section->setDescription($description);
    
    $isClosed = false;
    if (isset($_POST["s_is_closed"]) && intval($_POST['s_is_closed']) === 1) {
        $isClosed = true;
    }

    $section->setIsClosed($isClosed);
    
    $validator = new SectionValidator($section);
    if ($validator->isValid()) {
        $user =  new User();
        $user->setId(AuthUser::getId());
        $section->setUser($user);
        
        $sectionRepository = new SectionRepository();
        $sectionRepository->save($section);
        
        FlashMessage::add("Formularz został zapisany");
        header("Location: " . $baseUrl);
        die();
    } else {
        $errors = array_merge($errors, $validator->getErrors());
    }
}

$menu = "section-add";
$messages = FlashMessage::get();

include '/../library/Layout/Header.php';

include '_form.php';

include '/../library/Layout/Footer.php';