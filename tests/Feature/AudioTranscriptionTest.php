<?php

namespace Tests\Feature;

use App\Espinoso\DeliveryServices\TelegramDelivery;
use App\Espinoso\Espinoso;
use App\Facades\GuzzleClient;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AudioTranscriptionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testInfoGetAudioData()
    {
        $espinoso = resolve(Espinoso::class);
        $espinoso->setDelivery(resolve(TelegramDelivery::class));
        $text = $espinoso->transcribe($this->makeAudioMessage());
        $this->assertContains('mensaje para espinoso', $text);
    }
}
