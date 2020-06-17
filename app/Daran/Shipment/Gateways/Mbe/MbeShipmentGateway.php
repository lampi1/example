<?php

namespace App\Daran\Shipment\Gateways\Mbe;

use App\Daran\Shipment\ShipmentGateway;
use App\Daran\Shipment\ShippableOrder;
use App\Daran\Shipment\Exceptions\ShipmentException;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Log;

class MbeShipmentGateway implements ShipmentGateway
{
    protected $order;

    /**
     * Set the shippable order.
     *
     * @param ShippableOrder $order
     *
     * @return ShipmentGateway
     */
    public function setOrder(ShippableOrder $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Calculate the cost of shipping.
     *
     * @return float $cost
     */
    public function calculateCost()
    {
        $cost = 8;

        if(!$this->order){
            return $cost;
        }

        $client = new \SoapClient(config('shipment.gateways.mbe.options.server_url'), array(
            'soap_version'=>SOAP_1_2,
    		'cache_wsdl'=>WSDL_CACHE_NONE,
    		'trace'=>true,
    		'ssl_method'=>'SOAP_SSL_METHOD_TLS',
            'connection_timeout'=>15,
            'exceptions' => true,
            'encoding' => 'UTF-8',
            'stream_context'=> stream_context_create(array('ssl'=> array(
                'verify_peer'=>false,
                'verify_peer_name'=>false,
            )))
        ));

        $items = array();
        foreach($this->order->order_details as $detail){
            $items[] = [
                'Weight' => $detail->qty * $detail->item->weight,
                'Dimensions' => [
                    'Lenght' => '45.00',
                    'Height' => '5.00',
                    'Width' => '45.00'
                ]
            ];
        }

        $obj = array('RequestContainer' => [
            'System' => 'IT',
            'Credentials' => [
                'Username' => config('shipment.gateways.mbe.options.username'),
                'Passphrase' => config('shipment.gateways.mbe.options.password')
            ],
            'InternalReferenceID' => '1',
            'ShippingParameters' => [
                'DestinationInfo' => [
                    'ZipCode' => $this->order->address->zip,
                    'City' => $this->order->address->city,
                    'Country' => 'IT'
                ],
                'ShipType' => 'EXPORT',
                'PackageType' => 'GENERIC',
                'Items' => $items
            ]
        ]);
        $obj_master = array('ShippingOptionsRequest'=>$obj);
        $response = $client->__soapCall("ShippingOptionsRequest", $obj_master);
        try{
            foreach(reset($response->RequestContainer->ShippingOptions) as $opt){
                if($opt->Service == 'SSE'){
                    $cost = $opt->NetShipmentTotalPrice;
                    break;
                }
            }
        }catch(\Exception $e){}

        return $cost;
    }

    /**
     * Request a delivery for the current order
     *
     * @return float $cost
     */
    public function requestDelivery()
    {
        if(!$this->order){
            return;
        }

        $client = new \SoapClient(config('shipment.gateways.mbe.options.server_url'), array(
            'soap_version'=>SOAP_1_2,
    		'cache_wsdl'=>WSDL_CACHE_NONE,
    		'trace'=>true,
    		'ssl_method'=>'SOAP_SSL_METHOD_TLS',
            'connection_timeout'=>15,
            'exceptions' => true,
            'encoding' => 'UTF-8',
            'stream_context'=> stream_context_create(array('ssl'=> array(
                'verify_peer'=>false,
                'verify_peer_name'=>false,
            )))
        ));

        $items = array();
        $products = array();
        $total_weight = 0;
        foreach($this->order->order_details as $detail){
            $total_weight =+ ($detail->qty * $detail->item->weight);
            $products[] = [
                'SKUCode' => $detail->item->code.'-'.$detail->item->variant,
                'Description' => $detail->item->name,
                'Quantity' => $detail->qty
            ];
        }
        $tot_pacchi = ceil($total_weight/2.5);
        for($i=0;$i<$tot_pacchi;$i++){
            $items[] = [
                'Weight' => '2.5',
                'Dimensions' => [
                    'Lenght' => '45.00',
                    'Height' => '5.00',
                    'Width' => '45.00'
                ]
            ];
        }

        $obj = array('RequestContainer' => [
            'System' => 'IT',
            'Credentials' => [
                'Username' => config('shipment.gateways.mbe.options.username'),
                'Passphrase' => config('shipment.gateways.mbe.options.password')
            ],
            'InternalReferenceID' => $this->order->id,
            'Recipient' => [
                'Name' => $this->order->address->name,
                'CompanyName' => $this->order->address->surname,
                'Address' => $this->order->address->street,
                'Address2' => $this->order->address->street_2,
                'Phone' => $this->order->address->phone,
                'ZipCode' => $this->order->address->zip,
                'City' => $this->order->address->city,
                'State' => $this->order->address->province,
                'Country' => 'IT',
                'Email' => $this->order->user->email,
                'Items' => $items
            ],
            'Shipment' => [
                'ShipperType' => 'MBE',
                'Description' => 'Ordine Nalini NR '.$this->order->id.' del '.$this->order->created_at->format('d/m/Y'),
                'COD' => 0,
                'Insurance' => 0,
                'Service' => 'SSE',
                'PackageType' => 'GENERIC',
                'Referring' => 'Magazzino e-commerce',
                'Items' => $items,
                'Products' => $products
            ]
        ]);
        $obj_master = array('ShipmentRequest'=>$obj);
        $response = $client->__soapCall("ShipmentRequest", $obj_master);
        if($response->RequestContainer->Status == 'OK'){
            return $response->RequestContainer->MasterTrackingMBE;
        }

        return null;
    }

    /**
     * Close daily shipping request
     *
     * @return float $cost
     */
    public function closeShipments($codici)
    {
        $client = new \SoapClient(config('shipment.gateways.mbe.options.server_url'), array(
            'soap_version'=>SOAP_1_2,
    		'cache_wsdl'=>WSDL_CACHE_NONE,
    		'trace'=>true,
    		'ssl_method'=>'SOAP_SSL_METHOD_TLS',
            'connection_timeout'=>15,
            'exceptions' => true,
            'encoding' => 'UTF-8',
            'stream_context'=> stream_context_create(array('ssl'=> array(
                'verify_peer'=>false,
                'verify_peer_name'=>false,
            )))
        ));

        $items = array($codici);

        $obj = array('RequestContainer' => [
            'SystemType' => 'IT',
            'Credentials' => [
                'Username' => config('shipment.gateways.mbe.options.username'),
                'Passphrase' => config('shipment.gateways.mbe.options.password')
            ],
            'InternalReferenceID' => '',
            'MasterTrackingsMBE' => $items
        ]);

        $obj_master = array('CloseShipmentsRequest'=>$obj);
        $response = $client->__soapCall("CloseShipmentsRequest", $obj_master);

        // Log::info(var_dump($response));

        if($response->RequestContainer->Status == 'OK'){
            return true;
        }else if($response->RequestContainer->Status == 'ERROR'){
            if($response->RequestContainer->Errors->Error->Id == 3){
                return true;
            }
        }

        return false;
    }

    /**
     * Check daily shipping status
     *
     * @return float $status
     */
    public function checkShipment($tracking_code)
    {
        $client = new \SoapClient(config('shipment.gateways.mbe.options.server_url'), array(
            'soap_version'=>SOAP_1_2,
    		'cache_wsdl'=>WSDL_CACHE_NONE,
    		'trace'=>true,
    		'ssl_method'=>'SOAP_SSL_METHOD_TLS',
            'connection_timeout'=>15,
            'exceptions' => true,
            'encoding' => 'UTF-8',
            'stream_context'=> stream_context_create(array('ssl'=> array(
                'verify_peer'=>false,
                'verify_peer_name'=>false,
            )))
        ));

        $obj = array('RequestContainer' => [
            'System' => 'IT',
            'Credentials' => [
                'Username' => config('shipment.gateways.mbe.options.username'),
                'Passphrase' => config('shipment.gateways.mbe.options.password')
            ],
            'InternalReferenceID' => '',
            'TrackingMBE' => $tracking_code
        ]);

        $obj_master = array('TrackingRequest'=>$obj);
        try{
            $response = $client->__soapCall("TrackingRequest", $obj_master);
        }catch(\Exception $e){
            return false;
        }

        if($response->RequestContainer->Status == 'OK'){
            return $response->RequestContainer->TrackingStatus;
        }

        return false;
    }
}
