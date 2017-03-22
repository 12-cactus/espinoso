<?php 
namespace App\Telegram\UpdateHandlers;

interface UpdateHandler 
{
	public function shouldHandle($updates, $context=null) ; 
	public function handle($updates, $context=null) ; 
}