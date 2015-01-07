<?php

include '/../library/CommonClasses.php';
require_once '/../library/Repository/TopicRepository.php';
require_once '/../library/Assertion/TopicAssertion.php';

$sectionId = isset($_GET["sectionId"]) ? (int) $_GET["sectionId"] : 0;
$topicId = isset($_GET["topicId"]) ? (int) $_GET["topicId"] : 0;
$token = isset($_GET["token"]) ?  $_GET["token"] : "";
$confirm = isset($_GET["confirm"]) ? (int) $_GET["confirm"] : 0;

$topicRepository = new TopicRepository();
$topic = $topicRepository->findById($topicId, $sectionId);

$topicAssertion = new TopicAssertion();
if (!$topic || !$topicAssertion->assertDeleteTopic() || $token !== AuthUser::getToken()) {
    include "/../library/Layout/PageNotFound.php";
}

if ($confirm === 1)
{
    $topicRepository->delete($topic);
    
    FlashMessage::add("Temat został usunięty");
    header("Location: " . $baseUrl . "topic/list.php?sectionId=" . $sectionId);
    die();
}

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
<a href="<?php echo "topic/remove.php?sectionId=" . $topic->getSection()->getId() . "&topicId=" . $topic->getId() . "&token=" .AuthUser::getToken() . "&confirm=1" ?>">TAK</a>
<a href="<?php echo "topic/list.php?sectionId=" . $topic->getSection()->getId() ?>">ANULUJ</a>



<?php
include '/../library/Layout/Footer.php';
