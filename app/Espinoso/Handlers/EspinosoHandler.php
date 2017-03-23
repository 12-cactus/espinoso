<?php 
namespace App\Espinoso\Handlers;

interface EspinosoHandler 
{
    public function shouldHandle($updates, $context=null) ; 
    public function handle($updates, $context=null) ; 
}