@extends('daran::layouts.master')

@section('title')
    @lang('daran::redirection.redirections')
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12 mb-2 text-right">
            @can('create redirection')
                <a href="{{ route('admin.redirections.create') }}" class="btn btn-primary">@lang('daran::common.create')</a>
            @endcan
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::redirection.redirections')</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-vuetable ref="vuetable"
                fields-array="Redirection"
                api-url="{{route('admin-api.redirections.index')}}"
                :paginate="25"
                :route-prefix="'admin.redirections'"
                :route-api-prefix="'admin-api.redirections'"
                :is-sortable="false"
                :sort-order="[{field: 'name', direction: 'asc'}]"
                >
            </daran-vuetable>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent

@endsection
