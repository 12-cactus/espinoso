<?php
namespace App\Telegram;

class EspinosoHandlers
{
    const configFile = 'espinoso_handlers.php';

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


    private function __construct()
    {
        $path = config_path( self::configFile );
        $this->_config = include($path);
    }

    private function cfg($name) 
    { 
        return $this->_config[$name]; 
    }

    private static function instance()
    {
        if ( ! self::$_instance )
            self::$_instance = new EspinosoHandlers;
        return self::$_instance; 
    }


}