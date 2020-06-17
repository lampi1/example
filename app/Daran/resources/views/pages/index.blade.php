@extends('daran::layouts.master')

@section('title')
    @lang('daran::page.pages')
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12 mb-2 text-right">
            <a href="{{ route('admin.page-categories.index') }}" class="btn btn-secondary">@lang('daran::common.categories')</a>
            @can('create page')
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">@lang('daran::common.create')</a>
            @endcan
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::page.pages')</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-vuetable ref="vuetable"
                fields-array="Page"
                api-url="{{route('admin-api.pages.index')}}?lang={{session('working_lang', Lang::getLocale())}}"
                :paginate="25"
                :route-prefix="'admin.pages'"
                :route-api-prefix="'admin-api.pages'"
                :is-sortable="false"
                :sort-order="[{field: 'title', direction: 'asc'}]"
                >
            </daran-vuetable>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent

@endsection
