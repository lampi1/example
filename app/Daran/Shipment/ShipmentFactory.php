<?php

namespace App\Daran\Shipment;

use Illuminate\Config\Repository;
use Illuminate\Support\Collection;
use App\Daran\Shipment\ShipmentGateway;

/**
 *
 */
class ShipmentFactory
{
    /**
     * @var Collection
     */
    protected $config;
    /**
     * @var array
     */
    protected $gateways = [];
    /**
     * Manager constructor.
     *
     * @param array $config
     */
    public function __construct()
    {
        $this->config = new Repository(config('shipment', []));
    }

    /**
     * @param string|null $name
     *
     * @return \App\Daran\Shipment\ShipmentGateway
     */
    public function gateway(string $name = null): ShipmentGateway
    {
        if (empty($name)) {
            $name = $this->getDefaultGateway();
        }
        if (empty($this->gateways[$name])) {
            $driver = $this->config->get("gateways.{$name}.driver");

            if (is_null($driver)) {
                throw new \InvalidArgumentException(sprintf('No driver found for gateway "%s".', $name));
            }
            $this->gateways[$name] = Factory::make($driver, $this->getGatewayOptions($name));
        }
        return $this->gateways[$name];
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function getGatewayOptions(string $name): array
    {
        return array_merge($this->config->get('default_options', []), $this->config->get("gateways.{$name}.options", []));
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getDefaultGateway()
    {
        $name = $this->config->get('default_gateway');
        if (empty($name)) {
            throw new \Exception('No default gateway configured.');
        }
        return $name;
    }

}
