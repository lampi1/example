@extends('daran::layouts.master')

@section('title')
    @lang('daran::order.order')
@endsection

@section('header_styles')
    @parent

@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::order.order_detail'): {{$order->uuid}}</h3>
        </div>
    </div>
    <div class="row form-template-1">
        <div class="col-12">
            <div class="bordered py-4 px-5 mb-4">
                <div class="form-group row border-bottom mb-3">
                    <div class="col-10">
                        <h4>@lang('daran::order.order')</h4>
                    </div>
                    <div class="col-2 text-right">
                        <img src="{{ asset('images/ship_'.$order->ship_status.'.png')}}" width="42px" height="42px" alt="@lang('daran::order.ship_status_text.'.$order->ship_status)" title="@lang('daran::order.ship_status_text.'.$order->ship_status)" />

                        <img src="{{ asset('images/payment_'.$order->status.'.png')}}" width="42px" height="42px" alt="@lang('daran::order.payment_status_text.'.$order->status)" title="@lang('daran::order.payment_status_text.'.$order->status)" />
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-2">
                        <label class="control-label">@lang('daran::order.number')</label>
                        <input type="text" readonly="readonly" value="{{$order->uuid}}" /></div>
                    <div class="col-2">
                        <label class="control-label">@lang('daran::order.date')</label>
                        <input type="text" readonly="readonly" value="{{$order->created_at->format('d-m-Y')}}" />
                    </div>
                    <div class="col-2">
                        <label class="control-label">@lang('daran::order.spese_spedizione')</label>
                        <input type="text" readonly="readonly" value="{{number_format($order->shipping_cost,2,',','.')}}" />
                    </div>
                    <div class="col-2">
                        <label class="control-label">@lang('daran::order.spese_pagamento')</label>
                        <input type="text" readonly="readonly" value="{{number_format($order->payment_cost,2,',','.')}}" />
                    </div>
                    <div class="col-2">
                        <label class="control-label">@lang('daran::order.spese_doganali')</label>
                        <input type="text" readonly="readonly" value="{{number_format($order->country_cost,2,',','.')}}" />
                    </div>
                </div>
                <div class='form-group row mb-3'>
                    <div class="col-2">
                        <label class="control-label">@lang('daran::order.coupon_code')</label>
                        <input type="text" readonly="readonly" value="@if($order->coupon){{$order->coupon->code}}@endif" />
                    </div>
                    <div class="col-2">
                        <label class="control-label">@lang('daran::order.coupon_discount')</label>
                        <input type="text" readonly="readonly" value="{{number_format($order->coupon_amount,2,',','.')}}" />
                    </div>
                    <div class="col-2">
                        <label class="control-label">@lang('daran::order.totale')</label>
                        <input type="text" readonly="readonly" value="{{number_format($order->gross_total,2,',','.')}}" />
                    </div>
                    <div class="col-2">
                        <label class="control-label">@lang('daran::order.payment_status')</label>
                        <input type="text" readonly="readonly" value="@lang('daran::order.payment_status_text.'.$order->status)" />
                    </div>
                    <div class="col-2">
                        <label class="control-label">@lang('daran::order.ship_status')</label>
                        <input type="text" readonly="readonly" value="@lang('daran::order.ship_status_text.'.$order->ship_status)" />
                    </div>
                </div>
                @if($order->total != $order->goods_amount)
                    <div class='form-group row mb-3'>
                        <div class="col-2">
                            <label class="control-label">@lang('daran::order.total_to_be_payed')</label>
                            <input type="text" readonly="readonly" value="{{number_format($order->total,2,',','.')}}" />
                        </div>
                        <div class="col-2">
                            <label class="control-label">@lang('daran::order.total_payed')</label>
                            <input type="text" readonly="readonly" value="{{number_format($order->goods_amount,2,',','.')}}" />
                        </div>
                    </div>
                @endif
                <div class='form-group row mb-3'>
                    <div class="col-3">
                        <label class="control-label">@lang('daran::order.name')</label>
                        <input type="text" readonly="readonly" value="{{$order->user->name}}" />
                    </div>
                    <div class="col-3">
                        <label class="control-label">@lang('daran::order.surname')</label>
                        <input type="text" readonly="readonly" value="{{$order->user->surname}}" />
                    </div>
                    <div class="col-3">
                        <label class="control-label">@lang('daran::order.email')</label>
                        <input type="text" readonly="readonly" value="{{$order->user->email}}" />
                    </div>
                    <div class="col-2">
                        <label class="control-label">@lang('daran::order.user_id')</label>
                        <input type="text" readonly="readonly" value="{{$order->user_id}}" />
                    </div>
                </div>
            </div>

            @if($order->address)
                <div class="bordered py-4 px-5 mb-4">
                    <div class="form-group row mb-3">
                        <div class="col-12 border-bottom">
                            <h4>@lang('daran::order.address')</h4>
                        </div>
                    </div>
                    <div class='form-group row mb-3'>
                        <div class="col-3">
                            <label class="control-label">@lang('daran::order.name')</label>
                            <input type="text" readonly="readonly" value="{{$order->address->name}}" />
                        </div>
                        <div class="col-3">
                            <label class="control-label">@lang('daran::order.surname')</label>
                            <input type="text" readonly="readonly" value="{{$order->address->surname}}" />
                        </div>
                        <div class="col-3">
                            <label class="control-label">@lang('daran::order.email')</label>
                            <input type="text" readonly="readonly" value="{{$order->address->email}}" />
                        </div>
                        <div class="col-2">
                            <label class="control-label">@lang('daran::order.phone')</label>
                            <input type="text" readonly="readonly" value="{{$order->address->phone}}" />
                        </div>
                    </div>
                    <div class='form-group row mb-3'>
                        <div class="col-4">
                            <label class="control-label">@lang('daran::order.street')</label>
                            <input type="text" readonly="readonly" value="{{$order->address->street.' '.$order->address->street_2}}" />
                        </div>
                        <div class="col-3">
                            <label class="control-label">@lang('daran::order.city')</label>
                            <input type="text" readonly="readonly" value="{{$order->address->city}}" />
                        </div>
                        <div class="col-1">
                            <label class="control-label">@lang('daran::order.province')</label>
                            <input type="text" readonly="readonly" value="{{$order->address->province}}" />
                        </div>
                        <div class="col-2">
                            <label class="control-label">@lang('daran::order.zip')</label>
                            <input type="text" readonly="readonly" value="{{$order->address->zip}}" />
                        </div>
                    </div>
                </div>
            @endif

            @if($order->invoice_address)
                <div class="bordered py-4 px-5 mb-4">
                    <div class='form-group row mb-3'>
                        <div class="col-12 border-bottom">
                            <h4>@lang('daran::order.invoice_address')</h4>
                        </div>
                    </div>
                    <div class='form-group row mb-3'>
                        <div class="col-4">
                            <label class="control-label">@lang('daran::order.ragsoc')</label>
                            <input type="text" readonly="readonly" value="{{$order->invoice_address->surname.' '.$order->invoice_address->name}}" />
                        </div>
                        <div class="col-3">
                            <label class="control-label">@lang('daran::order.email')</label>
                            <input type="text" readonly="readonly" value="{{$order->invoice_address->email}}" />
                        </div>
                        <div class="col-2">
                            <label class="control-label">@lang('daran::order.piva')</label>
                            <input type="text" readonly="readonly" value="{{$order->invoice_address->piva}}" />
                        </div>
                        <div class="col-2">
                            <label class="control-label">@lang('daran::order.cf')</label>
                            <input type="text" readonly="readonly" value="{{$order->invoice_address->cod_fisc}}" />
                        </div>
                    </div>
                    <div class='form-group row mb-3'>
                        <div class="col-4">
                            <label class="control-label">@lang('daran::order.street')</label>
                            <input type="text" readonly="readonly" value="{{$order->invoice_address->street.' '.$order->invoice_address->street_2}}" />
                        </div>

                        <div class="col-3">
                            <label class="control-label">@lang('daran::order.city')</label>
                            <input type="text" readonly="readonly" value="{{$order->invoice_address->city}}" />
                        </div>
                        <div class="col-1">
                            <label class="control-label">@lang('daran::order.province')</label>
                            <input type="text" readonly="readonly" value="{{$order->invoice_address->province}}" />
                        </div>
                        <div class="col-2">
                            <label class="control-label">@lang('daran::order.zip')</label>
                            <input type="text" readonly="readonly" value="{{$order->invoice_address->zip}}" />
                        </div>
                    </div>
                </div>
            @endif

            <div class="bordered py-4 px-5 mb-4">
                <div class='form-group row mb-3'>
                    <div class="col-12 border-bottom">
                        <h3>@lang('daran::order.payment')</h3>
                    </div>
                </div>
                <div class='form-group row mb-3'>
                    <div class="col-4">
                        <label class="control-label">@lang('daran::order.payment_method')</label>
                        <input type="text" readonly="readonly" value="{{$order->payment_method->name}}" />
                    </div>
                    <div class="col-2">
                        <label class="control-label">@lang('daran::order.payment_date')</label>
                        <input type="text" readonly="readonly" value="@if($order->payed_at) {{$order->payed_at->format('d-m-Y')}} @else Non Pagato @endif" />
                    </div>
                    @if($order->status == 'failed')
                        <div class="col-4">
                            <label class="control-label">@lang('daran::order.errore_pagamento')</label>
                            <input type="text" readonly="readonly" value="{{$order->status_message}}" />
                        </div>
                    @endif
                </div>
            </div>

                @if($order->order_details->count() > 0)
                    <div class='form-group row'>
                        <div class="col-12">
                            <h3>@lang('daran::order.dettagli')</h3>
                        </div>
                    </div>
                    <div class="bordered py-4 px-5 mb-4">
                        <div class="form-group row">
                            <div class="col-12 content-wrapper">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            {{-- <th>@lang('daran::order.code')</th> --}}
                                            <th>@lang('daran::order.product')</th>
                                            <th>@lang('daran::order.qty')</th>
                                            <th>@lang('daran::order.size')</th>
                                            <th>@lang('daran::order.price')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->order_details as $item)
                                            <tr>
                                                <td><img src="{{config('app.url').optional($item->item->images()->first())->filename}}" height="120px" /></td>
                                                {{-- <td>{{ $item->item->code }}</td> --}}
                                                <td>{!! $item->item->name.'<br />'.$item->item->code.'<br />'.optional($item->item->category)->name.'<br />'.optional($item->item->subcategory)->name !!}</td>
                                                <td>{{ $item->qty }}</td>
                                                <td>{{ $item->size_name }}</td>
                                                <td>{{ number_format($item->total,2,',','.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12 text-right">
                        @if($order->status == 'new' || $order->status == 'failed')
                            <a href="{{route('admin.order.update-state',['id'=>$order->id,'field'=>'status','value'=>'payed'])}}" class="btn btn-secondary">@lang('daran::order.operations.set_payed')</a>
                        @endif

                        @if($order->status == 'payed' && $order->ship_status == 'new')
                            @if($order->total == $order->goods_amount)
                                <a href="{{route('admin.order.update-state',['id'=>$order->id,'field'=>'ship_status','value'=>'delivered'])}}" class="btn btn-secondary">@lang('daran::order.operations.set_shipped')</a>
                            @else
                                <a href="{{route('admin.order.update-state',['id'=>$order->id,'field'=>'ship_status','value'=>'request_delivery'])}}" class="btn btn-secondary">@lang('daran::order.operations.set_waiting_payment')</a>
                            @endif
                        @endif

                        @if($order->ship_status == 'request_delivery' && $order->total != $order->goods_amount)
                            <a href="{{route('admin.order.update-state',['id'=>$order->id,'field'=>'ship_status','value'=>'new'])}}" class="btn btn-secondary">@lang('daran::order.operations.set_shipped')</a>
                        @endif
                    </div>
                </div>

            </div>
    </div>
@endsection

@section('footer_scripts')
    @parent

@endsection
