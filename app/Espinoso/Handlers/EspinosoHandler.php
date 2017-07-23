<?php namespace App\Espinoso\Handlers;

use Exception;
use Illuminate\Support\Facades\Log;

abstract class EspinosoHandler
{
    abstract public function handle($updates, $context = null);
    abstract public function shouldHandle($updates, $context = null);

    protected function isTextMessage($updates)
    {
    	return isset($updates->message) && isset($updates->message->text); 
    }

    public function handleError(Exception $e, $updates)
    {
        Log::error(json_encode($updates));
        Log::error($e);
    }

    public function __toString()
    {
        return self::class;
    }


}