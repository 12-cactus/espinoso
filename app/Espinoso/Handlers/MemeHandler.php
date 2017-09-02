<?php

namespace App\Espinoso\Handlers;

use Illuminate\Contracts\Logging\Log;
use Telegram\Bot\Objects\Message;

class MemeHandler extends EspinosoCommandHandler
{
    protected $allow_ignore_prefix = true;
    protected $pattern = "(?'cmd'meme)\s+((?'src'[^\s]+))\s+(\"(?'top'[^\"]+)\")\s+(\"(?'bottom'[^\"]+)\")?";

    protected $signature = "[espi] meme src top bottom color";
    protected $description = "te armo un meme y no me rompas mas las bolas";

    public function handle(Message $message): void
    {
        $src = $this->matches['src'];

        $img = \Image::make($src);
        $imgWidth = $img->width();
        $imgHeight = $img->height();

        $x = $imgWidth / 2;
        $y = $imgHeight - 60;


        $top = \Illuminate\Support\Str::upper($this->matches['top']);
        $bottom = \Illuminate\Support\Str::upper($this->matches['bottom']);
        $color = '000000';

        $img->text($top, $x, 60, function ($font) use ($color) {
            $font->file('fonts/Impact.ttf');
            $font->size(45);
            $font->align('center');
            $font->valign('bottom');
            $font->color($color);
        });

        $img->text($bottom, $x, $y, function ($font) use ($color) {
            $font->file('fonts/Impact.ttf');
            $font->size(45);
            $font->align('center');
            $font->valign('top');
            $font->color($color);
        });

        $img->save(public_path() . '/img/meme.jpg');
        $this->espinoso->replyImage(asset('img/meme.jpg'));
    }
}