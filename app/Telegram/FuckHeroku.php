<?php
namespace App\Telegram;
class FuckHeroku
{
	public static function log($loggable)
	{
		$file = dirname(__FILE__) . '/fuckheroku.log'; 
		$data = self::format_loggable($loggable); 
		file_put_contents($file, $data, FILE_APPEND);
	}

	private function format_loggable($loggable)
	{
		if ($loggable instanceof \Exception)
		{
			return json_encode(['msg'=> $loggable->getMessage(), 'trace' => $loggable->getTrace() ]);
		} else {
			return json_encode($loggable);
		}
	}

	public static function get_log()
	{
		$file = dirname(__FILE__) . '/fuckheroku.log'; 
		return file_get_contents($file); 
	}
}