<?php namespace App\Espinoso\DeliveryServices;

/**
 * Interface EspinosoDeliveryInterface
 * @package App\Espinoso\DeliveryServices
 */
interface EspinosoDeliveryInterface
{
    /**
     * @param array $params
     * @return mixed
     */
    public function sendMessage(array $params = []): void;
}
