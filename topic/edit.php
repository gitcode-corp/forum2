<?php

include '/../library/CommonClasses.php';
require_once '/../library/Repository/TopicRepository.php';
require_once '/../library/Assertion/TopicAssertion.php';
require_once '/../library/Validator/TopicValidator.php';

$sectionId = isset($_GET["sectionId"]) ? (int) $_GET["sectionId"] : 0;
$topicId = isset($_GET["topicId"]) ? (int) $_GET["topicId"] : 0;
$topicRepository = new TopicRepository();
$topic = $topicRepository->findById($topicId, $sectionId);

$topicAssertion = new TopicAssertion();
if (!$topic || !$topicAssertion->assertEditTopic($topic)) {
    include "/../library/Layout/PageNotFound.php";
}

$section = $topic->getSection();
$errors = array(
    "t_name" => array(),
    "t_description" => array(),
    "t_is_closed" => array()
);

$isAdminForm = false;
if ($guard->isAccessGranted("ROLE_CHANGE_TOPIC_STATUS")) {
    $isAdminForm = true;
}

if ($_POST) {
    $name = (isset($_POST["t_name"])) ? $_POST['t_name'] : "";
    $description = (isset($_POST["t_description"])) ? $_POST['t_description'] : "";
    $topic->setName($name);
    $topic->setDescription($description);
    
    $isClosed = false;
    if ($isAdminForm) {
        if (isset($_POST["t_is_closed"]) && intval($_POST['t_is_closed']) === 1) {
            $isClosed = true;
        }
    }

    $topic->setIsClosed($isClosed);
    
    $validator = new TopicValidator($topic);
    if ($validator->isValid()) {
        $topicRepository->save($topic);
        
        FlashMessage::add("Formularz został zapisany");
        header("Location: " . $baseUrl . "post/list.php?sectionId=" . $sectionId . "&topicId=" . $topic->getId());
        die();
    } else {
        $errors = array_merge($errors, $validator->getErrors());
    }
}

$menu = "topic-add";
$messages = FlashMessage::get();

include '/../library/Layout/Header.php';

include '_form.php';

include '/../library/Layout/Footer.php';