<?php

namespace App\Daran\Payment;

/**
 * Interface PaymentGateway.
 */
interface PaymentGateway
{
    const PAYMENT_RESULT_OK = 'authorized';
    const PAYMENT_RESULT_DECLINED = 'declined';
    const PAYMENT_RESULT_CANCELLED_BY_CARDHOLDER = 'cancelledByCardHolder';
    const PAYMENT_RESULT_FAILED = 'failed';
    const PAYMENT_TIMED_OUT = 'timeout';

    /**
     * Set the order to be paid.
     *
     * @param $order
     *
     * @return PaymentGateway
     */
    public function setOrder(PayableOrder $order);

    /**
    * Start the payment procedure
    * @return string
    */
    public function initPayment();

    /**
    * Verify the payment procedure
    * @return string
    */
    public function verifyPayment($request);


    /**
     * Determine the result of the payment
     *
     * @param null $gatewayResponse
     *
     * @return string
     */
    //public function getPaymentResult($gatewayResponse = null);
}
