<?php

require_once '/../Entity/Topic.php';
require_once '/../Entity/Section.php';
require_once '/../Guard.php';
require_once '/../AuthUser.php';

class TopicAssertion
{
    private $guard;
    
    public function __construct()
    {
        $this->guard = new Guard();
    }
    
    public function assertAddTopic(Section $section)
    {        
        if (!$this->guard->isAccessGranted("ROLE_ADD_TOPIC") || $section->isClosed()) {
            return false;
        }
        
        return true;
    }
}
