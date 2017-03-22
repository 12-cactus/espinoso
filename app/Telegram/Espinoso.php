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
                Espinoso::handleError($e, $updates);
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

    private static function handleError(\Exception $e, $updates)
    {
        $message = "```" . $e->getMessage() . "```";
        $text = "No quiero amargarles la charla, pero falló algo gente: \n$message\n";

        $response = Telegram::sendMessage([
            'chat_id' => $updates->message->chat->id,
            'text' => $text
        ]);
        \Telegram\FuckHeroku::log($e);
        Log::error($e);
    }
}