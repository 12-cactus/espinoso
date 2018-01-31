<?php
/**
 * Created by PhpStorm.
 * User: sirdemian
 * Date: 23/01/18
 * Time: 14:00
 */

namespace App\Handlers;


use PHPUnit\Framework\Constraint\ArrayHasKey;

class BirthdayHandler extends MultipleCommand
{

    protected $patterns = [
        [
            'name' => 'set-birth',
            'pattern' => "cumple\s+(?'user'\@{0,1}\w+)\s+(?'birthday'[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4})$"
        ],[
            'name' => 'births-list',
            'pattern' => "(?'user'cumple\s\@{0,1}\w+)$"
        ]
    ];

    protected $birthArray = array();
    protected $ignorePrefix = true;
    protected $signature   = "[espi] cumple @user date
                              [espi] cumple @user";

    protected $description = "set y get de cumpleaños";


    protected function handleSetBirth(): void
    {
        $user = $this->matches['user'];
        $birthday = $this->matches['birthday'];

        $this->birthArray[$user] = $birthday;

        $this->espinoso->reply( "Agendado vieja! Saludaré en su momento");
    }

    protected function handleBirthsList(): void
    {
        $user = substr($this->matches['user'], 7);
        if(array_key_exists($user ,$this->birthArray)){
            $this->espinoso->reply($this->birthArray[$user]);
        }
        else {
            $this->espinoso->reply("cuando no tenes nada bueno q decir mejor no digas nada!!!");
        }
    }

}