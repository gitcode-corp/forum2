<?php

require_once 'Repository/RoleRepository.php';
require_once 'AuthUser.php';

class Guard
{
    public function isAccessGranted($role)
    {
        return in_array($role, AuthUser::getRoles());
    }
    
    public function isUserAuthenticated()
    {
        return (AuthUser::getId() !== 0);
    }
    
    public function isAuthUser($userId)
    {
        return (intval($userId) === AuthUser::getId());
    }
            
}
