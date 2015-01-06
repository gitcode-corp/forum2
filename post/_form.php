<a href="<?php echo "topic/list.php?sectionId=" . $sectionId ?>">
    <h3>DZIAŁ: <?php echo $topic->getSection()->getName() ?></h3>
</a>
<a href="<?php echo "post/list.php?sectionId=" . $sectionId . "&topicId=" . $topicId ?>">
    <h3>TEMAT: <?php echo $topic->getName() ?></h3>
</a>

<form method="POST">
    <?php foreach ($messages as $message) { ?>
        <div style="color: green"><?php echo $message["message"] ?></div>
    <?php } ?>
    
    <?php foreach ($errors["p_content"] as $error) { ?>
        <div style="color: red"><?php echo $error ?></div>
    <?php } ?>
        
    <div>
        <label>Treść:</label><br/>
        <textarea name="p_content" rows="4" cols="74" ><?php echo $post->getContent() ?></textarea>
    </div>
    
    <br /><br />
    <input type="submit" value="zapisz" />
    
</form>
