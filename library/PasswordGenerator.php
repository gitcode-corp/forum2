<?php

class PasswordGenerator
{
    public static function generate($plainPassword, $salt = null)
    {
        if ($salt === null) {
           $salt = substr(md5(rand()), 7, 12); 
        }
        
        $password = sha1($plainPassword . $salt);
        
        return ["password" => $password, "salt" => $salt];
    }
}
