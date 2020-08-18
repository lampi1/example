@extends('daran::layouts.master')

@section('title')
    @lang('daran::item.packaging-types')
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12 mb-2 text-right">
            <a href="{{ route('admin.packaging-types.create') }}" class="btn btn-primary">@lang('daran::common.create')</a>
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::item.packaging-types')</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-vuetable ref="vuetable"
                fields-array="PackagingType"
                api-url="{{route('admin-api.packaging-types.index')}}"
                :paginate="25"
                :route-prefix="'admin.packaging-types'"
                :route-api-prefix="'admin-api.packaging-types'"
                :is-sortable="true"
                :sort-order="[{field: 'priority', direction: 'asc'}]"
                >
            </daran-vuetable>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent

@endsection
