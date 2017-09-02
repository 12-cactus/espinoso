<?php

namespace App\Espinoso\Handlers;

use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Telegram\Bot\Objects\Message;

class MemeHandler extends EspinosoCommandHandler
{
    protected $allow_ignore_prefix = true;
    protected $pattern = "(?'cmd'meme)\s+((?'src'[^\s]+))\s+(\"(?'top'[^\"]+)\")(\s+(\"(?'bottom'[^\"]+)\")?)?";

    protected $signature = "[espi] meme src top bottom";
    protected $description = "te armo un meme y no me rompas mas las bolas";

    public function handle(Message $message): void
    {
        $meme = $this->generateMeme($this->matches['src'],
            $this->matches['top'],
            $this->matches['bottom'] ?? null);

        $this->espinoso->replyImage($meme);
    }

    public function generateMeme($src, $top, $bottom = null): string
    {
        $fontSize = 45;
        $fontFile = 'fonts/Impact.ttf';
        $color = '000000';
        $storagePath = public_path() . '/img/meme.jpg';
        $assetPath = asset('img/meme.jpg');

        $img = Image::make($src);
        $x = $img->width() / 2;
        $y = $img->height() - 60;
        $textMargin = 60;
        $top = Str::upper($top);
        $bottom = Str::upper($bottom);

        $img->text($top, $x, $textMargin, function ($font) use ($color, $fontSize, $fontFile) {
            $font->file($fontFile);
            $font->size($fontSize);
            $font->align('center');
            $font->valign('bottom');
            $font->color($color);
        });

        if (!is_null($bottom)) {
            $img->text($bottom, $x, $y, function ($font) use ($color, $fontSize, $fontFile) {
                $font->file($fontFile);
                $font->size($fontSize);
                $font->align('center');
                $font->valign('top');
                $font->color($color);
            });
        }

        $img->save($storagePath);

        return $assetPath;
    }
}