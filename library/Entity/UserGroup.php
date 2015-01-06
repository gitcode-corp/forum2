<?php

require_once 'User.php';
require_once 'Group.php';

class UserGroup
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
     * @var Group
     */
    private $group;
 
    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }
    
    public function getGroup()
    {
        return $this->group;
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
    
    public function setGroup(Group $group)
    {
        $this->group = $group;
        return $this;
    }
}
