<?php
namespace App\Telegram;

class Espinoso
{
    public static function getRegisteredHandlers() 
    {
        $handlerClasses = config("espinoso.handlers");

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
}