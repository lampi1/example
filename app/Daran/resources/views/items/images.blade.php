@extends('daran::layouts.master')

@section('title')
    @lang('daran::item.manage-images')
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-6 mb-2 text-left">
            <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
        </div>
        <div class="col-6 mb-2 text-right">
            <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">@lang('daran::item.items')</a>
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::item.manage-images')</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-item-images ref="item-images"
                :id="{{$id}}"
                :route-prefix="'admin.items'"
                :route-api-prefix="'admin-api.items'"
                api-url="{{route('admin-api.items.add-image',['id'=>$id])}}"
                :is-sortable="true"
                >
            </daran-item-images>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent

@endsection
