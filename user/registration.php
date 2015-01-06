<?php

include '/../library/CommonClasses.php';
require_once '/../library/Repository/UserRepository.php';
require_once '/../library/Repository/RoleRepository.php';
require_once '/../library/Entity/User.php';
require_once '/../library/PasswordGenerator.php';
require_once '/../library/Session.php';
require_once '/../library/Validator/UserValidator.php';

if ($guard->isUserAuthenticated()) {
    header("Location: " . $baseUrl);
    die();
}

$menu = "registration";
$user = new User();
$errors = array(
    "u_username" => array(),
    "u_password" => array(),
    "u_email" => array(),
);

if ($_POST) {
    $username = (array_key_exists("u_username", $_POST)) ? $_POST['u_username'] : "";
    $password = (array_key_exists("u_password", $_POST)) ? $_POST['u_password'] : "";
    $passwordReapeated = (array_key_exists("repeat_password", $_POST)) ? $_POST['repeat_password'] : "";
    $email = (array_key_exists("u_email", $_POST)) ? $_POST['u_email'] : "";
    
    $user->setUsername($username);
    $user->setEmail($email);
    $user->setPassword($password);
    
    $validator = new UserValidator($user, $passwordReapeated);
    
    if ($validator->isValid()) {
        $data= PasswordGenerator::generate($password);
        $user->setPassword($data["password"]);
        $user->setSalt($data["salt"]);
        
        $userRepository = new UserRepository();
        $userRepository->save($user);
        
        FlashMessage::add("Dane zostały zapisane. Mozesz sie zalogowac.");
        header("Location: " . $baseUrl );
        die();
    } else {
        $errors = array_merge($errors, $validator->getErrors());
    }
    
}

include '/../library/Layout/Header.php';
?>

        <form method="POST"> 
            <?php foreach ($errors["u_username"] as $error) { ?>
                <div style="color: red"><?php echo $error ?></div>
            <?php } ?>

            <div>
                <label>Login:</label><br/>
                <input name="u_username" value="<?php echo $user->getUsername() ?>" >
            </div>

            <?php foreach ($errors["u_email"] as $error) { ?>
                <div style="color: red"><?php echo $error ?></div>
            <?php } ?>

            <div>
                <label>E-mail:</label><br/>
                <input name="u_email" value="<?php echo $user->getEmail() ?>" >
            </div>

            <?php foreach ($errors["u_password"] as $error) { ?>
                <div style="color: red"><?php echo $error ?></div>
            <?php } ?>

            <div>
                <label>Haslo:</label><br/>
                <input type="password" name="u_password" value="" >
            </div>

            <div>
                <label>Powtorz haslo:</label><br/>
                <input type="password" name="repeat_password" value="" >
            </div>

            <br /><br />
            <input type="submit" value="zapisz" />

        </form>

 <?php
 include '/../library/Layout/Footer.php';