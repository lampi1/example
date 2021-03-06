@extends('daran::layouts.master')

@section('title')
    @lang('daran::item.subcategories')
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12 mb-2 text-right">
            <a href="{{ route('admin.families.index') }}" class="btn btn-secondary">@lang('daran::item.families')</a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">@lang('daran::item.categories')</a>
            <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">@lang('daran::item.items')</a>
            @can('create item')
                <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary">@lang('daran::common.create')</a>
            @endcan
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::item.subcategories')</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-vuetable ref="vuetable"
                fields-array="ItemSubcategory"
                api-url="{{route('admin-api.subcategories.index')}}"
                :paginate="25"
                :route-prefix="'admin.subcategories'"
                :route-api-prefix="'admin-api.subcategories'"
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
