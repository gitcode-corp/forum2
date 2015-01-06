<?php

require_once 'Validator.php';
require_once '/../Entity/Post.php';

class PostValidator extends Validator
{
    private $post;
    
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
    
    public function isValid()
    {
        $this->errors = [];

        $this->validContent();
        
        if ($this->getErrors()) {
            return false;
        }
        
        return true;
    }
    
    
    private function validContent()
    {
        $fieldName = "p_content";
        $fieldLabel = "Treść";
        
        $value = $this->post->getContent();
        $this->validIsNotEmpty($value, $fieldName, $fieldLabel);
        $this->validIsNotTooLong($value, $fieldName, $fieldLabel, 3000);
    }
}

