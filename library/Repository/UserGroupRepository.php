<?php

require_once 'Repository.php';
require_once '/../Entity/UserGroup.php';

class UserGroupRepository extends Repository
{
    public function save(UserGroup $userGroup)
    {
        $sql = "INSERT INTO users_groups (`user_id`, `security_group_id`) ";
        $sql .= "VALUES(";
        $sql .= "'". $this->escapeString($userGroup->getUser()->getId()) ."', ";
        $sql .= "'". $this->escapeString($userGroup->getGroup()->getId()) ."' ";
        $sql .= ")";
        
        $id = $this->_insert($sql);
        $userGroup->setId($id);
        
        return $userGroup;
    }
}
