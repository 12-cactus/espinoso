<?php
namespace App\Espinoso\Helpers;

class Msg
{
	protected $msg; 
	protected $parse_mode; 

	public static function md($msg)
	{
		return new Msg($msg, 'Markdown');
	}

	public static function html($msg)
	{
		return new Msg($msg, 'HTML');
	}

	public static function plain($msg)
	{
		return new Msg($msg);
	}

	public function build($pattern, $updates) 
	{
		$text = $this->parseMsg($pattern, $updates);
        return [
	        'chat_id' => $updates->message->chat->id,
			'text' => $text,
			'parse_mode' => $this->parse_mode,
    	];
    }

	private function __construct($msg, $parse_mode=null)
	{
		$this->parse_mode = $parse_mode;
		$this->msg = $msg ; 
	}

    private function parseMsg($pattern, $updates)
    {
		$msg = $this->msg;
        if (is_array($msg))
        {
        	$text = $this->choose($msg);
        } else if ( is_callable($msg) )
        {
            $text = $msg($pattern, $updates);
        } else 
        {
            $text = $msg; 
        }
        return $text ; 
    }

    private function choose($responses) 
    {
        $key = array_rand($responses);
        return $responses[$key];
    }

}