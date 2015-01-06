<?php

include 'library/CommonClasses.php';
require_once 'library/Repository/SectionRepository.php';

$sectionRepository = new SectionRepository();
$sections = $sectionRepository->findAll();
$messages = FlashMessage::get();

include 'library/Layout/Header.php';
?>

    <?php foreach ($messages as $message) { ?>
        <div style="color: green"><?php echo $message["message"] ?></div>
    <?php } ?>
        
    <div id="forum_main">
        <div id="forum_content">
            <div id="comment_section">
                <ol class="comments first_level">        
                    <?php foreach ($sections as $section) { ?>
                        <li>
                            <div class="comment_box commentbox1">
                                <div class="comment_text">
                                    <div class="comment_author">
                                        <a href="<?php echo "topic/list.php?sectionId=".$section->getId() ?>" style="color: #081e30"><?php echo $section->getName(); ?></a>
                                        <span class="date">Tematów: <?php echo $section->getAmountTopics(); ?></span>
                                    </div>
                                    <p><?php echo $section->getDescription(); ?></p>
                                    <?php if ($section->getLastTopic()) { ?>
                                        <p>
                                            Ostatni post w: <?php echo $section->getLastTopic()->getName() ?>
                                            przez: <?php echo $section->getLastPost()->getUsername() ?>
                                        </p>
                                    <?php } ?>

                                    <p style="margin-top: 15px">
                                        <?php if ($guard->isAccessGranted("ROLE_EDIT_SECTION")) { ?>
                                            <a href="<?php echo "section/edit.php?sectionId=" . $section->getId(); ?>">EDYTUJ</a>
                                        <?php } ?>

                                        <?php if ($guard->isAccessGranted("ROLE_DELETE_SECTION")) { ?>
                                            <a href="<?php echo "section/remove.php?sectionId=" .$section->getId() . "&token=".AuthUser::getToken() ?>">USUŃ</a>
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

        </div>

        <div class="cleaner"></div>
    </div>

<?php

include 'library/Layout/Footer.php';
