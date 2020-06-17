@extends('daran::layouts.master')

@section('title')
    @lang('daran::menu.edit-menu')
    @parent
@endsection


@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::menu.edit-menu')</h3>
        </div>
        <div class="col-12 mb-3">
            <div class="row" id="ref">
                <div class="col-12 form-template-1">
                    <daran-menu-builder ref="menubuilder"
                        :link-types='@json($linkTypes)'
                        :resource-id="{{$menu->id}}"
                        >
                    </daran-form-builder>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent

@endsection
