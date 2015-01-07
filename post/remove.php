<?php

include '/../library/CommonClasses.php';
require_once '/../library/Repository/PostRepository.php';
require_once '/../library/Assertion/PostAssertion.php';

$sectionId = isset($_GET["sectionId"]) ? (int) $_GET["sectionId"] : 0;
$topicId = isset($_GET["topicId"]) ? (int) $_GET["topicId"] : 0;
$postId = isset($_GET["postId"]) ? (int) $_GET["postId"] : 0;
$token = isset($_GET["token"]) ?  $_GET["token"] : "";
$confirm = isset($_GET["confirm"]) ? (int) $_GET["confirm"] : 0;

$postRepository = new PostRepository();
$post = $postRepository->findOne($postId, $topicId, $sectionId);

$postAssertion = new PostAssertion();
if (!$post || !$postAssertion->assertEditPost($post) || $token!==AuthUser::getToken()) {
    include "/../library/Layout/PageNotFound.php";
}

if ($confirm === 1)
{
    $postRepository->removeContent($post);
    
    FlashMessage::add("Post został usunięty");
    header("Location: " . $baseUrl . "post/list.php?sectionId=" . $sectionId . "&topicId=" . $topicId);
    die();
}

$topic = $post->getTopic();
$section = $topic->getSection();

include '/../library/Layout/Header.php';
?>

<a href="<?php echo "topic/list.php?sectionId=" . $topic->getSection()->getId() ?>">
    <h3>DZIAL: <?php echo $topic->getSection()->getName() ?></h3>
</a>
<a href="<?php echo "post/list.php?sectionId" . $topic->getSection()->getId() ."&topicId" . $topic->getId() ?>">
    <h3>TEMAT: <?php echo $topic->getName() ?></h3>
</a>

Czy napewno chcesz usunąć temat? <br/><br/>
<a href="<?php echo "post/remove.php?sectionId=" . $topic->getSection()->getId() . "&topicId=" . $topic->getId() ."&postId=" . $post->getId() . "&token=" .AuthUser::getToken() . "&confirm=1" ?>">TAK</a>
<a href="<?php echo "post/list.php?sectionId=" . $topic->getSection()->getId() . "&topicId=" . $topicId ?>">ANULUJ</a>



<?php
include '/../library/Layout/Footer.php';
