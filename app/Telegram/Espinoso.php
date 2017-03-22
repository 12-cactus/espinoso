<?php
namespace App\Telegram;

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
                $message = "```" . $e->getMessage() . "```";
                $text = "No quiero amargarles la charla, pero falló algo gente: \n$message\n";

                $response = Telegram::sendMessage([
                    'chat_id' => $updates->message->chat->id,
                    'text' => $text
                ]);

                Log::error($e);
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
}