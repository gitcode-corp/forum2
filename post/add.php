<?php

include '/../library/CommonClasses.php';
require_once '/../library/Repository/PostRepository.php';
require_once '/../library/Repository/TopicRepository.php';
require_once '/../library/Assertion/PostAssertion.php';
require_once '/../library/Entity/Post.php';
require_once '/../library/Entity/User.php';
require_once '/../library/Validator/PostValidator.php';

$sectionId = isset($_GET["sectionId"]) ? (int) $_GET["sectionId"] : 0;
$topicId = isset($_GET["topicId"]) ? (int) $_GET["topicId"] : 0;

$topicRepository = new TopicRepository();
$topic = $topicRepository->findById($topicId, $sectionId);

$postAssertion = new PostAssertion();
if (!$topic || !$postAssertion->assertAddPost($topic)) {
    include "/../library/Layout/PageNotFound.php";
}

$section = $topic->getSection();
$post = new Post();
$errors = array(
    "p_content" => array(),
);

if ($_POST) {
    $content = (array_key_exists("p_content", $_POST)) ? $_POST['p_content'] : "";
    $post->setContent($content);
    
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

$menu = "post-add";
$messages = FlashMessage::get();

include '/../library/Layout/Header.php';

include '_form.php';

include '/../library/Layout/Footer.php';