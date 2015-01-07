<a href="<?php echo "topic/list.php?sectionId=" . $section->getId() ?>">
    <h3>DZIAŁ: <?php echo $section->getName() ?></h3>
</a>

<form method="POST">
    <?php foreach ($messages as $message) { ?>
        <div style="color: green"><?php echo $message["message"] ?></div>
    <?php } ?>
        
    <?php foreach ($errors["t_name"] as $error) { ?>
        <div style="color: red"><?php echo $error ?></div>
    <?php } ?>

    <div>
        <label>Tytuł:</label><br/>
        <input type="text" name="t_name" value="<?php echo $topic->getName() ?>" maxlength="255" style="width:610px" />
    </div>
    
    <?php foreach ($errors["t_description"] as $error) { ?>
        <div style="color: red"><?php echo $error ?></div>
    <?php } ?>
        
    <div>
        <label>Podsumowanie:</label><br/>
        <textarea name="t_description" rows="4" cols="74" ><?php echo $topic->getDescription() ?></textarea>
    </div>
    
    <?php if ($isAdminForm) { ?>
        <?php foreach ($errors["t_is_closed"] as $error) { ?>
            <div style="color: red"><?php echo $error ?></div>
        <?php } ?>
    
    
        <div>
            <label>Status:</label>
            <select name="t_is_closed">
                <option value="0" <?php echo ($topic->isClosed() === false)? 'selected="selected"' : ""; ?>>Otwarty</option>
                <option value="1" <?php echo ($topic->isClosed() === true)? 'selected="selected"' : ""; ?>>Zamknięty</option>
            </select>
        </div>
    <?php } ?> 
        
    
    <br /><br />
    <input type="submit" value="zapisz" />
    
</form>
