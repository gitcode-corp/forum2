<?php

require_once '/../Entity/Topic.php';
require_once '/../Entity/Post.php';
require_once '/../Guard.php';
require_once '/../AuthUser.php';

class PostAssertion
{
    private $guard;
    
    public function __construct()
    {
        $this->guard = new Guard();
    }
    
    public function assertAddPost(Topic $topic)
    {
        if (!$this->guard->isAccessGranted("ROLE_ADD_POST") || $topic->isClosed() || $topic->getSection()->isClosed()) {
            return false;
        }
        
        return true;
    }
    
    public function assertEditPost(Post $post)
    {
        $topic = $post->getTopic();
        $isClosed = false;
        if ($topic->isClosed() || $topic->getSection()->isClosed()) {
            $isClosed = true;
        }
        
        $isGranted = false;
        if ($this->guard->isAccessGranted("ROLE_EDIT_ALL_POSTS")) {
            $isGranted = true;
        } elseif (
            !$isClosed
            && !$post->isEditedByAdmin()
            && ($this->guard->isAccessGranted("ROLE_EDIT_POST") 
            && $this->guard->isAuthUser($post->getUser()->getId()))
        ) {
            $isGranted = true;
        } 

        return $isGranted;
    }
    
    public function assertDeletePost(Post $post)
    {
        if ($this->guard->isAccessGranted("ROLE_DELETE_POST")) {
            return true;
        }
        
        return false;
    }
}

