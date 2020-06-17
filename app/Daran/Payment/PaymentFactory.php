<?php

namespace App\Daran\Payment;

use Illuminate\Config\Repository;
use Illuminate\Support\Collection;
use App\Daran\Payment\PaymentGateway;

/**
 *
 */
class PaymentFactory
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
        $this->config = new Repository(config('payment', []));
    }

    /**
     * @param string|null $name
     *
     * @return \App\Daran\Payment\PaymentGateway
     */
    public function gateway(string $name = null): PaymentGateway
    {
        if (empty($name)) {
            $name = $this->getDefaultGateway();
        }
        if (empty($this->gateways[$name])) {
            $driver = $this->config->get("gateways.{$name}.driver");
            
            if (is_null($driver)) {
                throw new \InvalidArgumentException(sprintf('No omnipay driver found for gateway "%s".', $name));
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
