<?php

namespace Espinaland\Responses;

use App\DeliveryServices\EspinosoDeliveryInterface;

/**
 * Class ThornyResponses
 * @package Espinaland\Responses
 */
abstract class ThornyResponses
{
    /**
     * @var EspinosoDeliveryInterface
     */
    protected $delivery;

    public function __construct(EspinosoDeliveryInterface $delivery)
    {
        $this->delivery = $delivery;
    }

    public function __toString()
    {
        return 'Thorny Response';
    }
}