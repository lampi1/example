<?php

namespace App\Daran\Shipment;

use App\Daran\Shipment\ShipmentGateway;
use App\Daran\Shipment\Gateways\Mbe\MbeShipmentGateway;
use App\Daran\Shipment\Gateways\Dhl\DhlShipmentGateway;
use App\Daran\Shipment\Gateways\Gls\GlsShipmentGateway;

class Factory
{
    /**
     * @param string $driver
     * @param array  $options
     *
     * @return \App\Daran\Shipment\ShipmentGateway
     */
    public static function make(string $driver, array $options)
    {
        switch ($driver) {
            case 'mbe':
                return new MbeShipmentGateway();
            case 'dhl':
                return new DhlShipmentGateway();
            case 'gls':
                return new GlsShipmentGateway();

            default:
                throw new \InvalidArgumentException(sprintf('No driver found for gateway "%s".', $name));
                break;
        }
    }
}
