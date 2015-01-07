<?php

include '/../library/CommonClasses.php';
require_once '/../library/Repository/PostRepository.php';
require_once '/../library/Repository/TopicRepository.php';
require_once '/../library/Assertion/PostAssertion.php';

$sectionId = isset($_GET["sectionId"]) ? (int) $_GET["sectionId"] : 0;
$topicId = isset($_GET["topicId"]) ? (int) $_GET["topicId"] : 0;

$topicRepository = new TopicRepository();
$topic = $topicRepository->findById($topicId, $sectionId);

if (!$topic) {
    include "/../library/Layout/PageNotFound.php";
}

$section = $topic->getSection();
$postRepository = new PostRepository();
$posts = $postRepository->findAllInTopic($topicId, $sectionId);

$postAssertion = new PostAssertion();
$postsData = [];
foreach ($posts as $post) {
    $postsData[] = [
        "post" => $post,
        "canBeEdit" => $postAssertion->assertEditPost($post),
        "canBeDelete" => $postAssertion->assertDeletePost()
    ];
}
$messages = FlashMessage::get();
include '/../library/Layout/Header.php';
?>

<div id="comment_section">
    <a href="<?php echo "topic/list.php?sectionId=" . $topic->getSection()->getId() ?>">
        <h3>DZIAŁ: <?php echo $topic->getSection()->getName() ?></h3>
    </a>
    <a href="<?php echo "post/list.php?sectionId=" . $topic->getSection()->getId() . "&topicId=" . $topic->getId() ?>">
        <h3>TEMAT: <?php echo $topic->getName() ?></h3>
    </a>
    
    <p><?php echo $topic->getDescription() ?></p>
    
    <?php foreach ($messages as $message) { ?>
        <div style="color: green"><?php echo $message["message"] ?></div>
    <?php } ?>
    <ol class="comments first_level">        
        <?php foreach ($postsData as $postData) { $post = $postData["post"] ?>
            <li>
                <div class="comment_box commentbox1">
                    <div class="comment_text">
                        <div class="comment_author">
                            Post napisany przez <?php echo $post->getUser()->getUsername(); ?>
                            <span class="date"><?php echo $post->getCreatedOnAsString(); ?></span>
                        </div>
                        <p><?php echo $post->getContent(); ?></p>
                            
                        <p style="margin-top: 15px">
                            <?php if ($postData["canBeEdit"]) { ?>
                                <a href="<?php echo "post/edit.php?sectionId=" . $topic->getSection()->getId() . "&topicId=" . $topic->getId() . "&postId=" . $post->getId() ?>">EDYTUJ</a>
                            <?php } ?>
                                
                            <?php if ($postData["canBeDelete"]) { ?>
                                <a href="<?php echo "post/remove.php?sectionId=" . $topic->getSection()->getId() . "&topicId=" . $topic->getId() . "&postId=" . $post->getId() . "&token=" . AuthUser::getToken() ?>">USUŃ</a>
                            <?php } ?>
                        </p>
                    </div>
                    
                    <div class="cleaner"></div>
                </div>  
            </li>
        <?php } ?>
    </ol>
</div>

<div class="cleaner"></div>

<?php
include '/../library/Layout/Footer.php';