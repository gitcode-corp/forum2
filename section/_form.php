
<form method="POST">
    <?php foreach ($messages as $message) { ?>
        <div style="color: green"><?php echo $message["message"] ?></div>
    <?php } ?>
        
    <?php foreach ($errors["s_name"] as $error) { ?>
        <div style="color: red"><?php echo $error ?></div>
    <?php } ?>

    <div>
        <label>Tytuł:</label><br/>
        <input type="text" name="s_name" value="<?php echo $section->getName() ?>" maxlength="255" style="width:610px" />
    </div>
    
    <?php foreach ($errors["s_description"] as $error) { ?>
        <div style="color: red"><?php echo $error ?></div>
    <?php } ?>
        
    <div>
        <label>Podsumowanie:</label><br/>
        <textarea name="s_description" rows="4" cols="74" ><?php echo $section->getDescription() ?></textarea>
    </div>
    

    <?php foreach ($errors["s_is_closed"] as $error) { ?>
        <div style="color: red"><?php echo $error ?></div>
    <?php } ?>


    <div>
        <label>Status:</label>
        <select name="s_is_closed">
            <option value="0" <?php echo ($section->isClosed() === false)? 'selected="selected"' : ""; ?>>Otwarty</option>
            <option value="1" <?php echo ($section->isClosed() === true)? 'selected="selected"' : ""; ?>>Zamknięty</option>
        </select>
    </div>

        
    
    <br /><br />
    <input type="submit" value="zapisz" />
    
</form>
