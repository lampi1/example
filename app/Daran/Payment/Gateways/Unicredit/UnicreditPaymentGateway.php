<?php

namespace App\Daran\Payment\Gateways\Unicredit;

use App\Daran\Payment\PaymentGateway;
use App\Daran\Payment\PayableOrder;
use App\Daran\Payment\Exceptions\PaymentException;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

require(__DIR__ . '/IGFS_CG_API/init/IgfsCgInit.php');
require(__DIR__ . '/IGFS_CG_API/init/IgfsCgVerify.php');
require(__DIR__ . '/IGFS_CG_API/tran/IgfsCgCredit.php');

class UnicreditPaymentGateway implements PaymentGateway
{
    protected $order;

    /**
     * Set the payable order.
     *
     * @param PayableOrder $order
     *
     * @return PaymentGateway
     */
    public function setOrder(PayableOrder $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
    * Start the payment procedure
    * @return string
    *
    * @throws PaymentException
    */
    public function initPayment()
    {
        $init = new \IgfsCgInit();
        $init->serverURL = config('payment.gateways.unicredit.options.server_url');
        $init->timeout = 15000;
        $init->tid = config('payment.gateways.unicredit.options.uid');//per servizio MyBank usare UNI_MYBK /UNI_ECOM
        $init->kSig = config('payment.gateways.unicredit.options.signature');
        $init->shopID = $this->order->getPaymentOrderId();
        $init->shopUserRef = $this->order->getCustomerEmail();
        $init->ShopUserName = $this->order->getCustomerName();
        $init->trType = "PURCHASE";
        $init->currencyCode = "EUR";
        $init->amount = $this->order->getPaymentAmount();
        $init->langID = $this->order->getCustomerLanguage();
        $init->notifyURL = LaravelLocalization::getLocalizedURL($this->order->getCustomerLanguage(),route('cart.notify',['id'=>$this->order->getPaymentOrderId()]));
        $init->errorURL = LaravelLocalization::getLocalizedURL($this->order->getCustomerLanguage(),route('cart.failed',['id'=>$this->order->getPaymentOrderId()]));
        $init->Description = $this->order->getPaymentDescription();
        $init->PaymentReason = $this->order->getPaymentDescription();
        $init->Recurrent = false;

        if (!$init->execute()) {
            throw new PaymentException($init->errorDesc);
        }

        $this->order->setPaymentUid($init->paymentID);

        return $init->redirectURL;
    }

    /**
    * Complete the payment procedure
    * @return string
    *
    * @throws PaymentException
    */
    public function verifyPayment()
    {
        $verify = new \IgfsCgVerify();
        $verify->serverURL = config('payment.gateways.unicredit.options.server_url');
        $verify->timeout = 15000;
        $verify->tid = config('payment.gateways.unicredit.options.uid');//per servizio MyBank usare UNI_MYBK /UNI_ECOM
        $verify->kSig = config('payment.gateways.unicredit.options.signature');
        $verify->shopID = $this->order->getPaymentOrderId();
        $verify->paymentID = $this->order->getPaymentUid();

        $notifyURL = LaravelLocalization::getLocalizedURL($this->order->getCustomerLanguage(),route('cart.notify',['id'=>$this->order->getPaymentOrderId()]));

        if (!$verify->execute()) {
            throw new PaymentException($verify->errorDesc);
        }

        return array('auth'=>$verify->authCode,'transaction'=>$verify->tranID);
    }

    /**
    * Credit a payment
    * @return string
    *
    * @throws PaymentException
    */
    public function credit($orderReturn)
    {
        $credit = new \IgfsCgCredit();
        $credit->serverURL = config('payment.gateways.unicredit.options.server_url');
        $credit->timeout = 15000;
        $credit->tid = config('payment.gateways.unicredit.options.uid');//per servizio MyBank usare UNI_MYBK /UNI_ECOM
        $credit->kSig = config('payment.gateways.unicredit.options.signature');
        $credit->shopID = $orderReturn->order->getPaymentOrderId().' - R.'.$orderReturn->id;
        $credit->amount = $orderReturn->getPaymentAmount();
        $credit->refTranID = $orderReturn->order->transaction_uid;
        if($orderReturn->getPaymentAmount() < $orderReturn->order->getPaymentAmount()){
            $credit->SplitTran = true;
        }else{
            $credit->SplitTran = false;
        }
        $credit->execute();

        return array('rc'=> $credit->rc,'error'=>$credit->errorDesc, 'transaction'=>$credit->tranID);
    }
}
