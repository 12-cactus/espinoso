<?php
namespace App\Telegram;
use Telegram\Bot\Laravel\Facades\Telegram;

class Espinoso
{
    public static function handleTelegramUpdates($updates)
    {
        $handlers = Espinoso::getRegisteredHandlers();

        foreach ($handlers as $key => $handler)
        {
            try 
            {
                if ($handler->shouldHandle($updates))
                    $handler->handle($updates);
            }  catch (\Exception $e) 
            {
                $this->handleError($e, $updates);
            }
        }
    }

    private static function getRegisteredHandlers() 
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

    private static handleError(\Exception $e, $updates)
    {
        $message = "```" . $e->getMessage() . "```";
        trigger_error(var_export($e->getTraceAsString(), true), E_USER_ERROR);
        $text = "No quiero amargarles la charla, pero falló algo gente: \n$message\n";

        $response = Telegram::sendMessage([
            'chat_id' => $updates->message->chat->id,
            'text' => $text
        ]);

        Log::error($e);
    }
}