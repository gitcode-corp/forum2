<?php

require_once 'Session.php';

class AuthUser
{
    public static function getToken()
    {
        $user = self::getUserData();
        if ($user) {
            return $user["token"];
        }
        return "";
    }
    
    public static function getId()
    {
        $user = self::getUserData();
        if ($user) {
            return $user["id"];
        }
        return 0;
    }
    
    public static function getUsername()
    {
        $user = self::getUserData();
        if ($user) {
            return $user["username"];
        }
        return "";
    }
        
    public static function getRoles()
    {
        $user = self::getUserData();
        if ($user) {
            return $user["roles"];
        }
        return [];
    }
    
    private static function getUserData()
    {
        return Session::get("USER", []);
    }
    
}
