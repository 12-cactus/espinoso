<?php 
namespace App\Espinoso\Handlers;

abstract class EspinosoHandler 
{
    abstract public function shouldHandle($updates, $context=null) ; 
    abstract public function handle($updates, $context=null) ; 
    
    protected function isTextMessage($updates)
    {
    	return isset($updates->message) && isset($updates->message->text); 
    }


}