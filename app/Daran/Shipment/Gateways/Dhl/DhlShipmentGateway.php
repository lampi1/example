<?php

namespace App\Daran\Shipment\Gateways\Dhl;

use App\Daran\Shipment\ShipmentGateway;
use App\Daran\Shipment\ShippableOrder;
use App\Daran\Shipment\Exceptions\ShipmentException;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\ArrayToXml\ArrayToXml;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Log;
use App\Models\Settings\Contact;
use App\Models\Country;
use GuzzleHttp\Client;

class DhlShipmentGateway implements ShipmentGateway
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

    private function getHeader()
    {
        $header = [
            'ServiceHeader' => [
                'MessageTime' => Carbon::now()->toIso8601String(),
                'MessageReference' => Str::random(30),
                'SiteID' => config('shipment.gateways.dhl.options.site_id'),
                'Password' => config('shipment.gateways.dhl.options.password')
            ]
        ];

        return $header;
    }

    private function getFrom()
    {
        $c = Contact::first();
        $from = [
            'CountryCode' => $c->country,
            'Postalcode' => $c->zip,
            'City' => $c->city
        ];

        return $from;
    }

    private function getTo()
    {
        $c = Country::where('name',$this->order->address->country)->first()->iso_code;
        $from = [
            'CountryCode' => $c,
            'Postalcode' => $this->order->address->zip,
            'City' => $this->order->address->city
        ];

        return $from;
    }

    /**
     * Calculate the cost of shipping.
     *
     * @return float $cost
     */
    public function calculateCost()
    {
        $default[] = array('name'=>'standard','price'=>12,'days'=>5,'code'=>'U');

        if(!$this->order){
            return collect($default);
        }

        $c = Country::where('name',$this->order->address->country)->first();
        if($c->iso_code == 'IT'){
            $cod_sped = 'N';
        }elseif($c->cee){
            $cod_sped = 'U';
        }else{
            $cod_sped = 'D';
        }

        $xml['GetQuote']['Request'] = $this->getHeader();
        $xml['GetQuote']['From'] = $this->getFrom();

        $pieces = array();
        foreach($this->order->order_details as $detail){
            for($i=0;$i<$detail->qty;$i++){
                $pieces[] = [
                    'Piece' => [
                        'PieceID' => $detail->id.$i,
                        'PackageTypeCode' => 'BOX',
                        // 'Height' => '15',
                        // 'Depth' => '30',
                        // 'Width' => '20',
                        'Weight' => '0.200',
                    ]
                ];
            }
        }

        $xml['GetQuote']['BkgDetails'] = [
            'PaymentCountryCode' => 'IT',
            'Date' => Carbon::tomorrow()->format('Y-m-d'),
            'ReadyTime' => 'PT10H21M',
            'ReadyTimeGMTOffset' => '+01:00',
            'DimensionUnit' => 'CM',
            'WeightUnit' => 'KG',
            'Pieces' => $pieces,
            'PaymentAccountNumber' => config('shipment.gateways.dhl.options.account_number'),
            'IsDutiable' => 'N',
            'QtdShp' => ['GlobalProductCode'=>$cod_sped]
        ];


        // $xml['GetQuote']['QtdShp'] = [
        //     'GlobalProductCode' => $cod_sped
        // ];

        $xml['GetQuote']['To'] = $this->getTo();

        // $xml['GetQuote']['Dutiable'] = [
        //     'DeclaredCurrency' => 'EUR',
        //     'DeclaredValue' => $this->order->total
        // ];



        $result = ArrayToXml::convert($xml, [
            'rootElementName' => 'p:DCTRequest',
            '_attributes' => [
                'xmlns:p'   => 'http://www.dhl.com',
                'xmlns:p1'  => 'http://www.dhl.com/datatypes',
                'xmlns:p2'  => 'http://www.dhl.com/DCTRequestdatatypes',
                'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation' => 'http://www.dhl.com DCT-req.xsd',
            ],
        ], true, 'UTF-8');

        //dd ($result);
        $client = new Client();
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=UTF8',
            ],
            'body' => $result,
        ];
        $response = $client->request('POST',config('shipment.gateways.dhl.options.server_url'),$options);
        $result =  new \SimpleXMLElement($response->getBody()->getContents());
//dd($xml);
//dd($result);
        if($result->Response && $result->Response->Status->ActionStatus == 'Error'){//dd($result->Response);
            return collect($default);
        }
//dd($result->GetQuoteResponse->BkgDetails);
        try{
            foreach($result->GetQuoteResponse->BkgDetails->QtdShp as $opt){
                //if($opt->GlobalProductCode->__toString() == 'U'){
                    $risultati[] = array('name'=>$opt->LocalProductName->__toString(),'code'=>$opt->GlobalProductCode->__toString(),'price'=>$opt->ShippingCharge->__toString(),'days'=>(intval($opt->TotalTransitDays->__toString())+intval($opt->PickupPostalLocAddDays->__toString())+intval($opt->DeliveryPostalLocAddDays->__toString())));
                //}
            }
            if(count($risultati) == 0){
                return collect($default);
            }
        }catch(\Exception $e){
            return collect($default);
        }

        return collect($risultati);
    }

    public function requestDelivery()
    {
        if(!$this->order){
            return $default;
        }

        $c = Contact::first();
        $xml['Request'] = $this->getHeader();
        $xml['RegionCode'] = 'EU';
        //$xml['RequestedPickupTime'] = 'N';
        $xml['NewShipper'] = 'N';
        $xml['LanguageCode'] = 'it';
        $xml['PiecesEnabled'] = 'Y';
        $xml['Billing'] = [
            'ShipperAccountNumber' => config('shipment.gateways.dhl.options.account_number'),
            'ShippingPaymentType' => 'S'
        ];
        $xml['Consignee'] = [
            'CompanyName' => substr($this->order->address->surname.' '.$this->order->address->name,0,60),
            'AddressLine' => substr($this->order->address->street.' '.$this->order->address->street_2,0,35),
            'City' => $this->order->address->city,
            'DivisionCode' => $this->order->address->province,
            'PostalCode' => $this->order->address->zip,
            'CountryCode' => Country::where('name',$this->order->address->country)->first()->iso_code,
            'CountryName' => $this->order->address->country,
            'Contact' => [
                'PersonName' => $this->order->user->surname.' '.$this->order->user->name,
                'PhoneNumber' => $this->order->address->phone,
                'Email' => $this->order->user->email
            ]
        ];
        // $xml['Commodity'] = [
        //     'CommodityCode' => $this->order->uuid,
        //     'CommodityName' => 'Order number '.$this->order->uuid
        // ];

        // $xml['Dutiable'] = [
        //     'DeclaredValue' => $this->order->total,
        //     'DeclaredCurrency' => 'EUR',
        //     'TermsOfTrade' => 'DAP',
        // ];

        $xml['Reference'] = [
            'ReferenceID' => '00008512',
            'ReferenceType' => 'CU'
        ];

        $tot_pezzi = 0;
        //$pieces = array();
        // dd($this->order->order_details->where('status','payed'));
        foreach($this->order->order_details->where('status','payed') as $det){
            $tot_pezzi += $det->qty;
            $pieces['Piece'][] = [
                    'PieceID' => $det->id,
                    'PackageType' => 'CP',
                    'Weight' => '0.2',
                    // 'DimWeight' => '0.2',
                    // 'Width' => '0.2',
                    // 'Height' => '15',
                    // 'Depth' => '30'
            ];
        }
        $xml['ShipmentDetails'] = [
            'NumberOfPieces' => $tot_pezzi,
            'Pieces' => $pieces,
            'Weight' => $tot_pezzi*0.2,
            'WeightUnit' => 'K',
            'GlobalProductCode' => $this->order->shipment_code,
            'LocalProductCode' => $this->order->shipment_code,
            'Date' => Carbon::tomorrow()->format('Y-m-d'),
            'Contents' => 'Abbigliamento',
            //'DoorTo' => 'DD',
            'DimensionUnit' => 'C',
            'PackageType' => 'CP',
            'IsDutiable' => 'N',
            'CurrencyCode' => 'EUR'
        ];

        $xml['Shipper'] = [
            'ShipperID' => config('shipment.gateways.dhl.options.account_number'),
            'CompanyName' => $c->business_name,
            'AddressLine' => $c->address,
            'City' => $c->city,
            'PostalCode' => $c->zip,
            'CountryCode' => $c->country,
            'CountryName' => $c->country,
            'Contact' => [
                'PersonName' => 'Daniele',
                'PhoneNumber' => $c->phone,
            ]
        ];

        $xml['Place'] = [
            'ResidenceOrBusiness' => 'B',
            'CompanyName' => $c->business_name,
            'AddressLine' => $c->address,
            'City' => $c->city,
            'CountryCode' => $c->country,
            'PostalCode' => $c->zip,
            'PackageLocation' => 'front desk'
        ];

        $xml['EProcShip'] = 'N';
        $xml['LabelImageFormat'] = 'PDF';
        $xml['RequestArchiveDoc'] = 'Y';
        $xml['NumberOfArchiveDoc'] = '1';

        $imagedata = Storage::disk('waybills')->get('logo.jpg');
        $base64 = base64_encode($imagedata);
        $xml['Label'] = [
            'HideAccount' => 'N',
            'LabelTemplate' => '8X4_A4_PDF',
            'Logo' => 'Y',
            'CustomerLogo' => [
                'LogoImage' => $base64,
                'LogoImageFormat' => 'JPG'
            ],
            'Resolution' => '200',
        ];

        $result = ArrayToXml::convert($xml, [
            'rootElementName' => 'req:ShipmentRequest',
            '_attributes' => [
                'xmlns:req'   => 'http://www.dhl.com',
                'xmlns:xsi'  => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation' => 'http://www.dhl.com ship-val-global-req.xsd',
                'schemaVersion' => '5.0',
            ],
        ], true, 'UTF-8');
        // dd($result);
        $client = new Client();
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=UTF8',
            ],
            'body' => $result,
        ];
        $response = $client->request('POST',config('shipment.gateways.dhl.options.server_url'),$options);
        // dd($response);
        try {
            $temp2 = utf8_encode($response->getBody()->getContents());
            $result = simplexml_load_string($temp2);
            // dd($result);
        } catch (Exception $e) {
            dump($e->getMessage());
            return false;
        }

        //$result =  new \SimpleXMLElement($response->getBody()->getContents());

        if($result->Response && $result->Response->Status->ActionStatus == 'Error'){
            return false;
        }

        if($result->Note->ActionNote == 'Success'){
            $this->order->tracking_code = $result->AirwayBillNumber;
            $this->order->shipped_at = Carbon::createFromFormat('Y-m-d',$result->ShipmentDate);
            $this->order->status = 'req_delivery';
            $this->order->save();

            $pdf_decoded = base64_decode($result->LabelImage->OutputImage);
            Storage::disk('waybills')->put($this->order->id.'.pdf', $pdf_decoded);
        }

        return true;
    }

    public function closeShipments($codici)
    {
        $c = Contact::first();
        $xml['Request'] = $this->getHeader();
        $xml['RegionCode'] = 'EU';
        $xml['Requestor'] = [
            'AccountType' => 'D',
            'AccountNumber' => config('shipment.gateways.dhl.options.account_number'),
            'RequestorContact' => [
                'PersonName' => 'Daniele',
                'Phone' => $c->phone,
            ],
            'CompanyName' => $c->business_name
        ];
        $xml['Place'] = [
            'LocationType' => 'B',
            'CompanyName' => $c->business_name,
            'Address1' => $c->address,
            'PackageLocation' => 'front desk',
            'City' => $c->city,
            'CountryCode' => $c->country,
            'PostalCode' => $c->zip
        ];
        $xml['Pickup'] = [
            'PickupDate' => Carbon::tomorrow()->format('Y-m-d'),
            'ReadyByTime' => '11:00',
            'CloseTime' => '19:30',
        ];
        $xml['PickupContact'] = [
            'PersonName' => 'Daniele',
            'Phone' => $c->phone,
        ];

        $result = ArrayToXml::convert($xml, [
            'rootElementName' => 'req:BookPURequest',
            '_attributes' => [
                'xmlns:req'   => 'http://www.dhl.com',
                'xmlns:xsi'  => 'http://www.w3.org/2001/XMLSchema-instance',
                'schemaVersion' => '1.0',
                'xsi:schemaLocation' => 'http://www.dhl.com book-pickup-global-req_EA.xsd',
            ],
        ], true, 'UTF-8');

//        return $result;
        $client = new Client();
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=UTF8',
            ],
            'body' => $result,
        ];
        $response = $client->request('POST',config('shipment.gateways.dhl.options.server_url'),$options);
        $result =  new \SimpleXMLElement($response->getBody()->getContents());

        if($result->Response && $result->Response->Status->ActionStatus == 'Error'){
            return false;
        }

        return true;
    }

    /**
     * Check daily shipping status
     *
     * @return float $status
     */
    public function checkShipment($tracking_code)
    {
        //$tracking_code='8564385550';
        $xml['Request'] = $this->getHeader();
        $xml['LanguageCode'] = 'en';
        $xml['AWBNumber'] = $tracking_code;
        $xml['LevelOfDetails'] = 'ALL_CHECK_POINTS';
        $xml['PiecesEnabled'] = 'S';

        $result = ArrayToXml::convert($xml, [
            'rootElementName' => 'req:KnownTrackingRequest',
            '_attributes' => [
                'xmlns:req'   => 'http://www.dhl.com',
                'xmlns:xsi'  => 'http://www.w3.org/2001/XMLSchema-instance',
                'xsi:schemaLocation' => 'http://www.dhl.com TrackingRequestKnown.xsd',
                'schemaVersion' => '1.0',
            ],
        ], true, 'UTF-8');

        $client = new Client();
        $options = [
            'headers' => [
                'Content-Type' => 'text/xml; charset=UTF8',
            ],
            'body' => $result,
        ];
        $response = $client->request('POST',config('shipment.gateways.dhl.options.server_url'),$options);
        $result =  new \SimpleXMLElement($response->getBody()->getContents());

        if($result->Response && $result->Response->Status->ActionStatus == 'Error'){
            return null;
        }

        foreach($result->AWBInfo as $stage){
            if($stage->ShipmentInfo->DlvyNotificationFlag == 'N'){ //consegnato
                return 'delivered';
            }
        }
        return 'on_delivery';
    }
}
