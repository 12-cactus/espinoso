<?php
namespace App\Telegram;

class Espinoso
{
    private static $_instance ; 
    private $_config ; 

    public static function getRegisteredHandlers() 
    {
        $handlerClasses = self::instance()->cfg('registered_handlers');

        $handlers = [] ; 
        foreach ($handlerClasses as $handlerClass)
        {
            if ( ! class_exists($handlerClass) )
            {
                Log::error($handlerClass . " no existe, se omite.");
                continue; 
            }
            $handlers[] = new $handlerClass; 
        }
        return $handlers;
    }

    private function cfg($name) 
    { 
        return config('espinoso.registered_handlers'); 
    }

    private static function instance()
    {
        if ( ! self::$_instance )
            self::$_instance = new EspinosoHandlers;
        return self::$_instance; 
    }


}