<?php

require_once 'Repository.php';
require_once 'GroupRepository.php';
require_once 'UserGroupRepository.php';
require_once '/../Entity/User.php';
require_once '/../Entity/UserGroup.php';

class UserRepository extends Repository
{
    public function updateAmountPost(User $user)
    {
        $sql = "UPDATE users SET ";
        $sql .= "amount_posts = amount_posts+1 ";
        $sql .= "WHERE id =" .$this->escapeString($user->getId());
        
        return $this->_update($sql);
    }
    
    public function save(User $user)
    {
        $sql = "INSERT INTO users (`username`, `email`, `password`, `salt`) ";
        $sql .= "VALUES(";
        $sql .= "'". $this->escapeString($user->getUsername()) ."', ";
        $sql .= "'". $this->escapeString($user->getEmail()) ."', ";
        $sql .= "'". $this->escapeString($user->getPassword()) ."', ";
        $sql .= "'". $this->escapeString($user->getSalt()) ."' ";
        $sql .= ")";
        
        $id = $this->_insert($sql);
        $user->setId($id);
        
        $groupRepository = new GroupRepository();
        $group = $groupRepository->findOneByName("USER");

        $userGroup = new UserGroup();
        $userGroup->setUser($user);
        $userGroup->setGroup($group);
        
        $userGroupRepository = new UserGroupRepository();
        $userGroupRepository->save($userGroup);
        
        return $user;

    }
    
    public function findOneByEmail($email)
    {
        $sql = "SELECT u.id AS u_id, u.username AS u_username, u.password AS u_password, u.salt AS u_salt, u.amount_posts AS u_amount_posts, u.created_on AS u_created_on ";
        $sql .= "FROM users u ";
        $sql .= "WHERE u.email LIKE '".$this->escapeString($email)."'";

        $row = $this->fetchOne($sql);

        if (!$row) {
            return null;
        }
        
        $user = new User();
        $user->setId($row['u_id']);
        $user->setUsername($row['u_username']);
        $user->setPassword($row['u_password']);
        $user->setSalt($row['u_salt']);
        $user->setAmountPosts($row['u_amount_posts']);
        $user->setCreatedOn(new \DateTime($row['u_created_on']));
        
        return $user;
    }
    
    public function findOneByUsername($username)
    {
        $sql = "SELECT u.id AS u_id, u.username AS u_username, u.password AS u_password, u.salt AS u_salt, u.amount_posts AS u_amount_posts, u.created_on AS u_created_on ";
        $sql .= "FROM users u ";
        $sql .= "WHERE u.username LIKE '".$this->escapeString($username)."'";

        $row = $this->fetchOne($sql);

        if (!$row) {
            return null;
        }
        
        $user = new User();
        $user->setId($row['u_id']);
        $user->setUsername($row['u_username']);
        $user->setPassword($row['u_password']);
        $user->setSalt($row['u_salt']);
        $user->setAmountPosts($row['u_amount_posts']);
        $user->setCreatedOn(new \DateTime($row['u_created_on']));
        
        return $user;
    }
}

