<?php

require_once 'Validator.php';
require_once '/../Entity/Topic.php';

class TopicValidator extends Validator
{
    private $topic;
    
    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }
    
    public function isValid()
    {
        $this->errors = [];
        
        $this->validName();
        $this->validDescription();
        
        if ($this->getErrors()) {
            return false;
        }
        
        return true;
    }
    
    private function validName()
    {
        $fieldName = "t_name";
        $fieldLabel = "Tytuł";
        
        
        $this->validIsNotEmpty($this->topic->getName(), $fieldName, $fieldLabel);
        $this->validIsNotTooShort($this->topic->getName(), $fieldName, $fieldLabel, 5);
        $this->validIsNotTooLong($this->topic->getName(), $fieldName, $fieldLabel, 250);
    }
    
    private function validDescription()
    {
        $fieldName = "t_description";
        $fieldLabel = "Podsumowanie";
        
        
        if ($this->topic->getDescription()) {
            return;
        }
        
        $this->validIsNotTooLong($this->topic->getDescription(), $fieldName, $fieldLabel, 2000);
    }
}
