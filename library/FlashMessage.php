<?php

require_once 'Session.php';

class FlashMessage
{
    public static function add($message, $type = null)
    {
        $messages = Session::get("flash", []);
        $messages[] = ["message" => $message, "type" => $type];
        Session::set("flash", $messages);
    }
    
    public static function get()
    {
        $messages = Session::get("flash", []);
        Session::remove("flash");
        
        return $messages;
    }
}