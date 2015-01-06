<?php

class Session
{
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function destroy()
    {
        session_destroy();
    }
    
    public static function id()
    {
        return session_id();
    }
    
    public static function regenerateId($delete_old_session = false)
    {
        session_regenerate_id($delete_old_session);
    }
    
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null)
    {
        if (self::has($key)) {
            return $_SESSION[$key];
        }
        
        return $default;
    }
    
    public static function remove($key)
    {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
    }
    
    public static function has($key)
    {
        return array_key_exists($key, $_SESSION);
    }
}
