<?php namespace App\Espinoso\Handlers;

use App\Espinoso\Espinoso;
use App\Espinoso\BrainNode;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Api as ApiTelegram;

class BrainHandler extends EspinosoHandler
{
    protected $allNodes;
    protected $matchedNodes;

    public function __construct(Espinoso $espinoso, ApiTelegram $telegram)
    {
        parent::__construct($espinoso, $telegram);

        $this->matchedNodes = collect([]);
        $this->allNodes = collect(config('brain.patterns'))->map(function ($data, $regex) {
            return new BrainNode($regex, $data);
        });
    }

    public function shouldHandle(Message $message): bool
    {
        $this->matchedNodes = $this->allNodes->filter(function ($node) use ($message) {
            $node->addIgnored($this->globalIgnored());
            return $node->matchMessage($message);
        });

        return $this->matchedNodes->isNotEmpty();
    }

    public function handle(Message $message)
    {
        $this->matchedNodes->each(function ($node) use ($message) {
            $this->telegram->sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'text'    => $node->pickReply(),
                'parse_mode' => 'Markdown'
            ]);
        });
    }

    /*
     * Internals
     */

    protected function globalIgnored()
    {
        return collect(config('brain.ignore_to'));
    }

//    public function handle(Message $message)
//    {
//        if ($this->ignoringSender($message->getFrom())) {
//            $fromName = $message->getFrom()->getFirstName();
//            $msg = Msg::md("Con vos no hablo porque no viniste al asado $fromName")->build($message);
//            $this->telegram->sendMessage($msg);
//            return;
//        }
//
//        foreach ($this->mappings() as $pattern => $response) {
//            if ( preg_match($pattern, $message->getText()) ) {
//                $msg = $this->buildMessage($response, $pattern, $message);
//                $this->telegram->sendMessage($msg);
//            }
//        }
//    }

//    private function buildMessage($response, $pattern, Message $message)
//    {
//        if ($response instanceof Msg)
//            return $response->build($message, $pattern);
//        else
//            return Msg::plain($response)->build($message, $pattern);
//    }
//
//    private function mappings()
//    {
//        return config('espinoso_data.ResponseByMatch.mappings');
//    }
//

//    private function ignoringSender($sender)
//    {
//        foreach ($this->ignoredNames() as $name)
//            if ( preg_match("/$name/i", $sender->first_name) )
//                return true ;
//        return false ;
//    }
    
}
