@extends('daran::layouts.master')

@section('title')
    @lang('daran::coupon.coupons')
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12 mb-2 text-right">
            @can('edit item')
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">@lang('daran::common.create')</a>
            @endcan
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::coupon.coupons')</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-vuetable ref="vuetable"
                fields-array="Coupon"
                api-url="{{route('admin-api.coupons.index')}}"
                :paginate="25"
                :route-prefix="'admin.coupons'"
                :route-api-prefix="'admin-api.coupons'"
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
