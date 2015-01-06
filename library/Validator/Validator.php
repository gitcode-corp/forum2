<?php

abstract class Validator
{
    abstract function isValid();
    
    const ERROR_EMPTY = "Pole '%s' nie moze byc puste.";
    const ERROR_TOO_LONG = "Pole '%s' moze miec max. %d znakow.";
    const ERROR_TOO_SHORT = "Pole '%s' musi miec min. %d znakow.";
    const ERROR_UNEXPECTED_FIELD = "Niedozwolone pole '%s'.";
    const ERROR_CSRF_TOKEN = "Zły token. Spróbuj ponownie.";
    const ERROR_OPTION_NOT_ALLOWED = "Pole '%s' zawiera niedozwoloną wartość.";
    const ERROR_FIELD_REQUIRED = "Pole '%s' jest wymagane.";
    
    protected $errors = [];
    
    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    
    protected function addError($field, $error)
    {
        $this->errors[$field][] = $error;
    }
    
    protected function validIsNotEmpty($value, $fieldName, $fieldLabel)
    {
        $isValid = true;
        $value = trim($value);
        if (!$value) {
            $error = sprintf(self::ERROR_EMPTY, $fieldLabel);
            $this->addError($fieldName, $error);
            $isValid = false;
        }
        

        if (empty($value)) {
            $error = sprintf(self::ERROR_EMPTY, $fieldLabel);
            $this->addError($fieldName, $error);
            $isValid = false;
        }
        
        return $isValid;
    }
    
    protected function validIsNotTooShort($value, $fieldName, $fieldLabel, $min)
    {
        $value = trim($value);
        if (strlen($value) < $min) {
            $error = sprintf(self::ERROR_TOO_SHORT, $fieldLabel, $min);
            $this->addError($fieldName, $error);
            
            return false;
        }
        
        return true;
    }
    
    protected function validIsNotTooLong($value, $fieldName, $fieldLabel, $max)
    {
        $value = trim($value);
        if (strlen($value) > $max) {
            $error = sprintf(self::ERROR_TOO_LONG, $fieldLabel, $max);
            $this->addError($fieldName, $error);
            
            return false;
        }
        
        return true;
    }
    
    protected function validIsInArray($value, $fieldName, $fieldLabel, array $allowed)
    {
        if (!in_array($value, $allowed)) {
            $error = sprintf(self::ERROR_OPTION_NOT_ALLOWED, $fieldLabel);
            $this->addError($fieldName, $error);
            
            return false;
        }
        
        return true;
    }
}
