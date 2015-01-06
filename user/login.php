<?php

include '/../library/CommonClasses.php';
require_once '/../library/Repository/UserRepository.php';
require_once '/../library/Repository/RoleRepository.php';
require_once '/../library/Entity/User.php';
require_once '/../library/PasswordGenerator.php';
require_once '/../library/Session.php';

if ($guard->isUserAuthenticated()) {
    header("Location: " . $baseUrl);
    die();
}

$menu = "login";
$error = "";

if ($_POST) {
    $username = (isset($_POST['username'])) ? $_POST["username"] : "";
    $password = (isset($_POST['password'])) ? $_POST["password"] : "";
    $userRepository = new UserRepository();
    $user = $userRepository->findOneByUsername($username);
    
    if (!$user) {
        $error = "Zły login lub hasło.";
    } else {
        $data = PasswordGenerator::generate($password, $user->getSalt());
        if ($data['password'] != $user->getPassword()) {
            $error = "Zły login lub hasło.";
        }
    }
    
    if (!$error) {
        $roleRepository = new RoleRepository();
        $roles = $roleRepository->findAllByUserId($user->getId());
        
        $roleName = [];
        foreach ($roles as $role) {
            $roleName[] = $role->getName();
        }
        
        $sessionData = [
            "id" => (int) $user->getId(), 
            "username" => $user->getUsername(), 
            "roles" => $roleName,
            "token" => substr(md5(rand()), 10, 15)
        ];
        
        Session::set("USER", $sessionData);
        
        header("Location: " . $baseUrl);
        die();
    }
}



include '/../library/Layout/Header.php';
?>

<form method="POST">
    <?php if ($error) { ?>
        <div style="color: red"><?php echo $error ?></div>
    <?php } ?>
    <label>Login:</label>
    <input type="text" name="username" />
    
    <label>Haslo:</label>
    <input type="password" name="password" />
    
    <input type="submit" value="zaloguj" />
</form>

<?php
include '/../library/Layout/Footer.php';