@extends('daran::layouts.master')

@section('title')
    @lang('daran::item.families')
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12 mb-2 text-right">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">@lang('daran::item.categories')</a>
            <a href="{{ route('admin.subcategories.index') }}" class="btn btn-secondary">@lang('daran::item.subcategories')</a>
            <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">@lang('daran::item.items')</a>
            @can('create item')
                <a href="{{ route('admin.families.create') }}" class="btn btn-primary">@lang('daran::common.create')</a>
            @endcan
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::item.families')</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-vuetable ref="vuetable"
                fields-array="Family"
                api-url="{{route('admin-api.families.index')}}"
                :paginate="25"
                :route-prefix="'admin.families'"
                :route-api-prefix="'admin-api.families'"
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
