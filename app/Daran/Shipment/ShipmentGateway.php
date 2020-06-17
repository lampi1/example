<?php

namespace App\Daran\Shipment;

/**
 * Interface PaymentGateway.
 */
interface ShipmentGateway
{
    /**
     * Set the order to be paid.
     *
     * @param $order
     *
     * @return ShipmentGateway
     */
    public function setOrder(ShippableOrder $order);


}
