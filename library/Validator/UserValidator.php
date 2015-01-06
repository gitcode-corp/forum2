<?php

require_once 'Validator.php';
require_once '/../Entity/User.php';
require_once '/../Repository/UserRepository.php';

class UserValidator extends Validator
{
    private $user;
    private $passwordRepeated;
    private $userRepository;
    
    public function __construct(User $user, $passwordRepeated)
    {
        $this->user = $user;
        $this->passwordRepeated = $passwordRepeated;
        $this->userRepository = new UserRepository();
    }
    
    public function isValid()
    {
        $this->errors = array();
        
        $this->validUsername();
        $this->validEmail();
        $this->validPassword();
        
        
        if ($this->getErrors()) {
            return false;
        }
        
        return true;
    }
    
    private function validUsername()
    {
        $fieldName = "u_username";
        $fieldLabel = "Login";
        
        $this->validIsNotEmpty($this->user->getUsername(), $fieldName, $fieldLabel);
        $this->validIsNotTooShort($this->user->getUsername(), $fieldName, $fieldLabel, 5);
        $this->validIsNotTooLong($this->user->getUsername(), $fieldName, $fieldLabel, 250);
        $this->validIsUniqueUsername($this->user->getUsername(), $fieldName, $fieldLabel);
    }
    
    private function validEmail()
    {
        $fieldName = "u_email";
        $fieldLabel = "E-mail";
        
        $this->validIsNotEmpty($this->user->getEmail(), $fieldName, $fieldLabel);
        $this->validIsEmail($this->user->getEmail(), $fieldName, $fieldLabel);
        $this->validIsUniqueEmail($this->user->getEmail(), $fieldName, $fieldLabel);
    }
    
    private function validPassword()
    {
        $fieldName = "u_password";
        $fieldLabel = "Hasło";
        
        $this->validIsNotTooShort($this->user->getPassword(), $fieldName, $fieldLabel, 5);
        $this->validIsNotTooLong($this->user->getPassword(), $fieldName, $fieldLabel, 25);
        
        if ($this->user->getPassword() !== $this->passwordRepeated) {
            $this->addError($fieldName, "Pole hasło i powtórz hasło muszą być takie same.");
        }
    }
    
    private function validIsUniqueUsername($username, $fieldName)
    {
        $user = $this->userRepository->findOneByUsername($username);
        if ($user) {
            $this->addError($fieldName, "Login jest juz zajety.");
            
            return false;
        }
        
        return true;
    }
    
    private function validIsUniqueEmail($email,$fieldName)
    {
        $user = $this->userRepository->findOneByEmail($email);
        
        if ($user) {
            $this->addError($fieldName, "E-mail jest juz zajety.");
            
            return false;
        }
        
        return true;
    }
    
    private function validIsEmail($email,$fieldName)
    {
        $value = trim($email);
        
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $error = "Wpisz poprawnie e-mail.";
            $this->addError($fieldName, $error);
            
            return false;
        }
        
        return true;
    }
}

