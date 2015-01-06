<?php

require_once 'User.php';
require_once 'Topic.php';

class Section
{
    /**
     * @var integer
     */
    private $id;
    
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $description;
    
    /**
     * @var int
     */
    private $amountTopics;
    
    /**
     * @var bool
     */
    private $isClosed;
    
    /**
     * @var \DateTime
     */
    private $createdOn;
    
    /**
     * @var Topic
     */
    private $lastTopic;
    
    /**
     * @var User
     */
    private $user;
    
    
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getAmountTopics()
    {
        return $this->amountTopics;
    }

    public function isClosed()
    {
        return $this->isClosed;
    }

    public function getCreatedOn() 
    {
        return $this->createdOn;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setAmountTopics($amountTopics)
    {
        $this->amountTopics = $amountTopics;
        return $this;
    }

    public function setIsClosed($isClosed)
    {
        $this->isClosed = $isClosed;
        return $this;
    }

    public function setCreatedOn(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;
        return $this;
    }
    
    public function getLastTopic()
    {
        return $this->lastTopic;
    }

    public function setLastTopic(Topic $lastTopic = null)
    {
        $this->lastTopic = $lastTopic;
        return $this;
    }
    
    public function getLastPost()
    {
        if ($this->getLastTopic()) {
            return $this->getLastTopic()->getLastPost();
        }
        
        return null;
    }
    
    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }
}
