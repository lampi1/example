@extends('daran::layouts.master')

@section('title')
    @lang('daran::order.orders')
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::order.orders')</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-vuetable ref="vuetable"
                fields-array="Order"
                api-url="{{route('admin-api.orders.index')}}"
                :paginate="25"
                :route-prefix="'admin.orders'"
                :route-api-prefix="'admin-api.orders'"
                :is-sortable="false"
                :sort-order="[{field: 'id', direction: 'desc'}]"
                >
            </daran-vuetable>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent

@endsection
