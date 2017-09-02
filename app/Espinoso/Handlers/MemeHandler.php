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

    protected $signature = "[espi] meme src top [bottom]";
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
        $textMargin = 60;
        $x = $img->width() / 2;
        $y = $img->height() - $textMargin;
        $top = Str::upper($top);
        $bottom = Str::upper($bottom);

        $img = $this->addText($img, $top, $x, $textMargin, $color, $fontSize, $fontFile, 'bottom');
        if (!is_null($bottom))
            $img = $this->addText($img, $bottom, $x, $y, $color, $fontSize, $fontFile, 'top');

        $img->save($storagePath);

        return $assetPath;
    }

    public function addText($img, $text, $x, $y, $color, $fontSize, $fontFile, $verticalAlign)
    {
        return $img->text($text, $x, $y, function ($font) use ($color, $fontSize, $fontFile, $verticalAlign) {
            $font->file($fontFile);
            $font->size($fontSize);
            $font->align('center');
            $font->valign($verticalAlign);
            $font->color($color);
        });
    }
}