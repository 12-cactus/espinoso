<?php
namespace App\Telegram;
class FuckHeroku
{
	public static function log($loggable)
	{
		$file = storage_path('logs/fuckheroku.log'); 
		$data = "\nSTART LOG------------\n . self::format_loggable($loggable) . "\nEND LOG------------\n; 
		file_put_contents($file, $data, FILE_APPEND);
	}
	public static function get_log()
	{
		$file = storage_path('logs/fuckheroku.log');
 		return file_get_contents($file); 
	}

	private static function format_loggable($loggable)
	{
		if ($loggable instanceof \Exception)
		{
			return json_encode(['msg'=> $loggable->getMessage(), 'trace' => $loggable->getTrace() ]);
		} else {
			return json_encode($loggable);
		}
	}

}