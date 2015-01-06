<?php

require_once 'Topic.php';

class Post
{
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var User
     */
    private $user;
    
    /**
     * @var bool
     */
    private $isEditedByAdmin;
    
    /**
     * @var string
     */
    private $content;
    
    /**
     * @var \DateTime
     */
    private $createdOn;
    
    /**
     * @var Topic
     */
    private $topic;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getUser()
    {
        return $this->user;
    }

    public function getUsername()
    {
        return $this->getUser()->getUsername();
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function getTopic()
    {
        return $this->topic;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function setCreatedOn(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;
        return $this;
    }

    public function setTopic(Topic $topic)
    {
        $this->topic = $topic;
        return $this;
    }
    
    public function getCreatedOnAsString()
    {
        return $this->createdOn->format("d-m-Y H:i:s");
    }
    
    public function isEditedByAdmin()
    {
        return $this->isEditedByAdmin;
    }

    public function setIsEditedByAdmin($isEditedByAdmin)
    {
        $this->isEditedByAdmin = $isEditedByAdmin;
        return $this;
    }


}
