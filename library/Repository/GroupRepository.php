<?php

require_once 'Repository.php';
require_once '/../Entity/Group.php';

class GroupRepository extends Repository
{
    public function findOneByName($name)
    {
        $sql = "SELECT sg.id AS sg_id, sg.name AS sg_name ";
        $sql .= "FROM security_groups sg ";
        $sql .= "WHERE sg.name LIKE '".$this->escapeString($name)."'";

        $row = $this->fetchOne($sql);

        if (!$row) {
            return null;
        }
        
        $group = new Group();
        $group->setId($row["sg_id"]);
        $group->setName($row["sg_name"]);
        
        return $group;
    }
}
