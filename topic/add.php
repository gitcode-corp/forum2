<?php

include '/../library/CommonClasses.php';
require_once '/../library/Repository/SectionRepository.php';
require_once '/../library/Assertion/TopicAssertion.php';
require_once '/../library/Entity/Topic.php';

$sectionId = isset($_GET["sectionId"]) ? (int) $_GET["sectionId"] : 0;
$sectionRepository = new SectionRepository();
$section = $sectionRepository->findById($sectionId);
$messages = FlashMessage::get();

$topicAssertion = new TopicAssertion();
if (!$section || !$topicAssertion->assertAddTopic($section)) {
    include "/../library/Layout/PageNotFound.php";
}

$topic = new Topic();
if ($_POST) {
    $name = (isset($_POST["t_name"])) ? $_POST['t_name'] : "";
    $escription = (isset($_POST["t_description"])) ? $_POST['t_description'] : "";
    $topic->setName($name);
    $topic->setDescription($description);
    
    $validator = new PostValidator($post);
    if ($validator->isValid()) {
        $user = new User();
        $user->setId(AuthUser::getId());
        
        $post->setTopic($topic);
        $post->setUser($user);
        $postRepository = new PostRepository();
        $postRepository->save($post);
        
        header("Location: " . $baseUrl . "post/list.php?sectionId=" . $sectionId . "&topicId=" . $topicId);
        die;
    } else {
        $errors = array_merge($errors, $validator->getErrors());
    }
}

$menu = "topic-add";
$messages = FlashMessage::get();

include '/../library/Layout/Header.php';

include '_form.php';

include '/../library/Layout/Footer.php';