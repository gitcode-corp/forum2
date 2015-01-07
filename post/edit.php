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
$postId = isset($_GET["postId"]) ? (int) $_GET["postId"] : 0;

$postRepository = new PostRepository();
$post = $postRepository->findOne($postId, $topicId, $sectionId);

$postAssertion = new PostAssertion();
if (!$post || !$postAssertion->assertEditPost($post)) {
    include "/../library/Layout/PageNotFound.php";
}

$topic = $post->getTopic();
$section = $topic->getSection();
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
        
        if ($guard->isAccessGranted("ROLE_EDIT_ALL_POSTS")) {
            $post->setIsEditedByAdmin(true);
        }
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

$messages = FlashMessage::get();

include '/../library/Layout/Header.php';

include '_form.php';

include '/../library/Layout/Footer.php';