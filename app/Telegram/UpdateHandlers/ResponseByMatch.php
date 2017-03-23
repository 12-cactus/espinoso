<?php
namespace App\Telegram\UpdateHandlers ; 

use Telegram\Bot\Laravel\Facades\Telegram;

class ResponseByMatch implements UpdateHandler
{
    private $mappings = [
        '/macri/i' => 'Gato',
        '/marcos/i' => '¿Quisiste decir Markos?',
        '/maximo/i' => 'Para programarme no usaron ni un solo if ;)',
        '/facu/i'  => 'Facu... ese tipo es medio puto',
        '/dan/i'  => 'ese tiene tatuado pattern matching en el culo!'
    ];

    public function shouldHandle($updates, $context=null) 
    {
        // me aseguro que el request que vino trae los datos que quiero... 
        if ( ! (isset($updates->message) && isset($updates->message->text)) )
            return false ; 

        foreach ($this->mappings as $needle => $response)
            if ( preg_match($needle, $updates->message->text) )
                return true ; 
        return false ; 
    }

    public function handle($updates, $context=null)
    {
        foreach ($this->mappings as $needle => $response)
        {
            if ( preg_match($needle, $updates->message->text) ) 
            {
                $response = Telegram::sendMessage([
                    'chat_id' => $updates->message->chat->id,
                    'text' => $response
                ]);
            }
        }
    }
}