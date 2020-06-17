<?php

namespace App\Daran\Payment\Gateways\Axepta;

use App\Daran\Payment\PaymentGateway;
use App\Daran\Payment\PayableOrder;
use App\Daran\Payment\Exceptions\PaymentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Events\OrderReceived;

require(__DIR__ . '/IGFS_CG_API/init/IgfsCgInit.php');
require(__DIR__ . '/IGFS_CG_API/init/IgfsCgVerify.php');
require(__DIR__ . '/IGFS_CG_API/tran/IgfsCgCredit.php');

class AxeptaPaymentGateway implements PaymentGateway
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
        $init->serverURL = config('payment.gateways.axepta.options.server_url');
        $init->timeout = 15000;
        $init->kSig = config('payment.gateways.axepta.options.signature');
        $init->tid = config('payment.gateways.axepta.options.uid');
        $init->shopID = $this->order->getPaymentOrderId();
        $init->shopUserRef = $this->order->getCustomerEmail();
        $init->ShopUserName = $this->order->getCustomerName();
        $init->trType = "PURCHASE";
        $init->amount = ($this->order->totOrdine+$this->order->contrassegno+$this->order->spedizione)*100;
        $init->currencyCode = "EUR";
        $init->notifyURL = LaravelLocalization::getLocalizedURL(Lang::getLocale(),route('cart.notify',['id'=>$this->order->getPaymentOrderId()]));
        $init->errorURL = LaravelLocalization::getLocalizedURL(Lang::getLocale(),route('cart.failed',['id'=>$this->order->getPaymentOrderId()]));
        $init->description = $this->order->getPaymentDescription();
        $init->callbackURL = route('axepta.webhook');
        $init->langID = Lang::getLocale();
        if (!$init->execute()) {
            throw new PaymentException($init->rc.' '.$init->errorDesc);
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
    public function verifyPayment($request)
    {
        $verify = new \IgfsCgVerify();
        $verify->serverURL = config('payment.gateways.axepta.options.server_url');
        $verify->timeout = 15000;
        $verify->tid = config('payment.gateways.axepta.options.uid');//per servizio MyBank usare UNI_MYBK /UNI_ECOM
        $verify->kSig = config('payment.gateways.axepta.options.signature');
        $verify->shopID = $this->order->getPaymentOrderId();
        $verify->paymentID = $this->order->getPaymentUid();

        if (!$verify->execute()) {
            throw new PaymentException($verify->rc.' '.$verify->errorDesc);
        }
        return array('status'=>($verify->rc == 'IGFS_000' ? 'payed' : $verify->rc),'auth'=>$verify->authCode,'message'=>$verify->errorDesc);
    }

    public function callback($request)
    {
        $verify = new \IgfsCgVerify();
        $verify->serverURL = config('payment.gateways.axepta.options.server_url');
        $verify->timeout = 15000;
        $verify->tid = config('payment.gateways.axepta.options.uid');//per servizio MyBank usare UNI_MYBK /UNI_ECOM
        $verify->kSig = config('payment.gateways.axepta.options.signature');
        $verify->shopID = $request->shopID;
        $verify->paymentID = $request->paymentID;

        if (!$verify->execute()) {
            $esito='ko';
        }else{
            $esito='ok';
        }

        $order = DB::table('ordini')->where('num_ordine',$request->shopID)->first();

        if($order){
            if($order->esito != $esito){
                DB::table('ordini')->where('num_ordine',$request->shopID)->update(['esito'=>$esito]);
            }
        }
        return array('auth'=>$verify->authCode,'transaction'=>$verify->tranID);
    }
}
