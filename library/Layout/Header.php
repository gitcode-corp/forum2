<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <base href="<?php echo $baseUrl ?>" /> 
        <title>PHP forum</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <link href="library/Layout/style.css" rel="stylesheet" type="text/css" />

    </head>
    
    <body>
        <div id="forum_wrapper">
            <div id="forum_header"> </div>
            
            <div id="forum_menu">
                <ul>
                    <li><a <?php echo ($menu === "home")? 'class="current' : ""; ?> href="index.php">Home</a></li>
                    <?php if ($guard->isUserAuthenticated()) { ?>
                        <li><a href="user/logout.php" class="last">Wyloguj</a></li>
                    <?php } else { ?>
                        <li><a <?php echo ($menu === "login")? 'class="current' : ""; ?> href="user/login.php" class="last">Zaloguj</a></li>
                    <?php } ?>
                        
                    <?php if (!$guard->isUserAuthenticated()) { ?>
                        <li><a <?php echo ($menu === "registration")? 'class="current' : ""; ?>  href="user/registration.php" class="last">Rejestracja</a></li>
                    <?php } ?>
                        
                    <?php if ($guard->isAccessGranted("ROLE_ADD_SECTION")) { ?>
                        <li style="float: right">
                            <a <?php echo ($menu === "section-add")? 'class="current' : ""; ?> href="section/add.php">+ dzial</a> 
                        </li>
                    <?php } ?>
                        
                    <?php if ($guard->isAccessGranted("ROLE_ADD_TOPIC") && isset($section) && $section->getId() && !$section->isClosed()) { ?>
                        <li style="float: right">
                            <a <?php echo ($menu === "topic-add")? 'class="current' : ""; ?> href="<?php  echo "topic/add.php?sectionId=" . $section->getId() ?>">+ temat</a> 
                        </li>
                    <?php } ?>
                        
                    <?php  if ($guard->isAccessGranted("ROLE_ADD_POST") && isset($topic) && !$topic->isClosed() && !$topic->getSection()->isClosed()) { ?>
                        <li style="float: right">
                            <a <?php echo ($menu === "post-add")? 'class="current' : ""; ?> href="<?php echo "post/add.php?sectionId=" . $topic->getSection()->getId() . "&topicId=" . $topic->getId() ?>">+ post</a> 
                        </li>
                    <?php } ?> 
                </ul>   	
            </div> <!-- end of forum_menu -->
