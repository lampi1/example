@extends('daran::layouts.master')

@section('title')
    @lang('daran::item.manage-related')
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row form-template-1">
        <div class="col-12 mb-2 text-right">
            <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">@lang('daran::item.items')</a>
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::item.manage-related')</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-related-items ref="related"
                :id="{{$id}}"
                :route-prefix="'admin.items'"
                :route-api-prefix="'admin-api.items'"
                :is-sortable="false"
                >
            </daran-related-items>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent

@endsection
