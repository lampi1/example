@extends('daran::layouts.master')

@section('title')
    @lang('daran::slider.sliders')
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12 mb-2 text-right">
            @can('create slider')
                <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">@lang('daran::common.create')</a>
            @endcan
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::slider.sliders')</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-vuetable ref="vuetable"
                fields-array="Slider"
                api-url="{{route('admin-api.sliders.index')}}?lang={{session('working_lang', Lang::getLocale())}}"
                :paginate="25"
                :route-prefix="'admin.sliders'"
                :route-api-prefix="'admin-api.sliders'"
                :is-sortable="true"
                :sort-order="[{field: 'name', direction: 'asc'}]"
                >
            </daran-vuetable>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent

@endsection
