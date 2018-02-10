<?php

namespace Tests\Feature;

use App\Espinoso;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Espinaland\Deliveries\EspinosoDeliveryInterface;

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
