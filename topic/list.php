<?php

include '/../library/CommonClasses.php';
require_once '/../library/Repository/SectionRepository.php';
require_once '/../library/Repository/TopicRepository.php';
require_once '/../library/Assertion/TopicAssertion.php';

$sectionId = isset($_GET["sectionId"]) ? (int) $_GET["sectionId"] : 0;
$sectionRepository = new SectionRepository();
$section = $sectionRepository->findById($sectionId);
$messages = FlashMessage::get();
if (!$section) {
    include "/../library/Layout/PageNotFound.php";
}

$topicRepository = new TopicRepository();
$topics = $topicRepository->findAllInSectionWithLastPost($sectionId);

$topicAssertion = new TopicAssertion();

include '/../library/Layout/Header.php';
?>


<a href="<?php echo "topic/list.php?sectionId=" . $section->getId() ?>">
    <h3>DZIAŁ: <?php echo $section->getName() ?></h3>
</a>

<div id="comment_section">
    <?php foreach ($messages as $message) { ?>
        <div style="color: green"><?php echo $message["message"] ?></div>
    <?php } ?>
    <ol class="comments first_level">
        <?php foreach ($topics as $topic) { ?>
            <li>
                <div class="comment_box commentbox1">
                    <div class="comment_text">
                        <div class="comment_author">
                            <a href="<?php echo "post/list.php?sectionId=" . $section->getId() . "&topicId=" . $topic->getId() ?>" style="color: #081e30"><?php echo $topic->getName(); ?></a>
                            <span class="date">Postów: <?php echo $topic->getAmountPosts(); ?></span>
                        </div>
                        <p><?php echo $topic->getDescription(); ?></p>
                        <?php if ($topic->getLastPost()) { ?>
                            <p>
                                Ostatni post: <?php echo $topic->getLastPost()->getCreatedOnAsString() ?>
                                przez: <?php echo $topic->getLastPost()->getUser()->getUsername() ?>
                            </p>
                        <?php } ?>
                            
                        <p style="margin-top: 15px">
                            <?php if ($topicAssertion->assertEditTopic($topic)) { ?>
                                <a href="<?php echo "topic/edit.php?sectionId=" . $section->getId() . "&topicId=" . $topic->getId() ?>">EDYTUJ</a>
                            <?php } ?>
                                
                            <?php if ($topicAssertion->assertDeleteTopic()) { ?>
                                <a href="<?php echo "topic/remove.php?sectionId=" . $section->getId() . "&topicId=" . $topic->getId() . "&token=" . AuthUser::getToken() ?>">USUŃ</a>
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
