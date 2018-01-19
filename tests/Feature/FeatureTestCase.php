<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Espinoso;
use App\DeliveryServices\EspinosoDeliveryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class FeatureTestCase
 * @package Tests
 */
class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Espinoso
     */
    protected $espinoso;
    /**
     * @var EspinosoDeliveryInterface
     */
    protected $delivery;

    protected function setUp()
    {
        parent::setUp();

        $this->espinoso = resolve(Espinoso::class);
    }
}
