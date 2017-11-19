<?php

namespace Tests\Feature;

use App\Espinoso\DeliveryServices\EspinosoDeliveryInterface;
use App\Espinoso\Espinoso;
use Tests\DBTestCase;

/**
 * Class FeatureTestCase
 * @package Tests
 */
class FeatureTestCase extends DBTestCase
{
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
