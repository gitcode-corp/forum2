<?php

require_once 'Validator.php';
require_once '/../Entity/Section.php';

class SectionValidator extends Validator
{
    private $section;
    
    public function __construct(Section $section)
    {
        $this->section = $section;
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
        $fieldName = "s_name";
        $fieldLabel = "Tytuł";
        
        
        $this->validIsNotEmpty($this->section->getName(), $fieldName, $fieldLabel);
        $this->validIsNotTooShort($this->section->getName(), $fieldName, $fieldLabel, 5);
        $this->validIsNotTooLong($this->section->getName(), $fieldName, $fieldLabel, 250);
    }
    
    private function validDescription()
    {
        $fieldName = "s_description";
        $fieldLabel = "Podsumowanie";
        
        
        if ($this->section->getDescription()) {
            return;
        }
        
        $this->validIsNotTooLong($this->section->getDescription(), $fieldName, $fieldLabel, 2000);
    }
}
