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

    /**
     * @param array $params
     */
    public function sendImage(array $params = []): void;
}
