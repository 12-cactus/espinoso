<?php
namespace App\Espinoso\Handlers ; 
use App\Espinoso\Helpers\Msg;
use Telegram\Bot\Laravel\Facades\Telegram;

class Instrospection extends EspinosoHandler
{
    const KEYWORD = 'reflecshon';

    public function shouldHandle($updates, $context=null) 
    {
        $filename = $this->extractFile($updates->message->text);
        return  $this->isTextMessage($updates)
                    && preg_match($this->regex(), $updates->message->text)
                    && file_exists($this->filePath($filename));
    }

    public function handle($updates, $context=null)
    {
        $code = file_get_contents($this->filePath($this->extractFile($updates->message->text)));
        if ($code)
        {
            $msg = Msg::md("```\n" . $code . "\n```")->build($updates);
            Telegram::sendMessage($msg);
        }

    }

    private function regex()
    {
        return "/^" . self::KEYWORD . "[ ]*(.*)$/i";
    }

    /**
     * @param $filename
     * @return bool
     */
    protected function filePath($filename)
    {
        return base_path($filename);
    }

    private function extractFile($text)
    {
        preg_match($this->regex(), $text, $matches);
        return $matches[1];
    }

}