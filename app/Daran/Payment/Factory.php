<?php

namespace App\Daran\Payment;

use App\Daran\Payment\PaymentGateway;
use App\Daran\Payment\Gateways\Axepta\AxeptaPaymentGateway;
use App\Daran\Payment\Gateways\Paypal\PaypalPaymentGateway;

class Factory
{
    /**
     * @param string $driver
     * @param array  $options
     *
     * @return \App\Daran\Payment\PaymentGateway
     */
    public static function make(string $driver, array $options)
    {
        switch ($driver) {
            case 'axepta':
                return new AxeptaPaymentGateway();
            case 'paypal':
                return new PaypalPaymentGateway();
            case 'unicredit':
                return new UnicreditPaymentGateway();

            default:
                throw new \InvalidArgumentException(sprintf('No driver found for gateway "%s".', $name));
                break;
        }
    }
}
