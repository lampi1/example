@extends('daran::layouts.master')

@section('title')
    @lang('daran::form.edit-form')
    @parent
@endsection


@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::form.edit-form')</h3>
        </div>
        <div class="col-12 mb-3">
            <div class="row" id="ref" template_id="{{$template_id}}" mode="{{$mode}}" locale_group="{{$locale_group}}" locale="{{$locale}}" locales='@json($locales)' >
                <div class="col-12 form-template-1">
                    <daran-form-builder ref="formbuilder"
                        :locales='@json($locales)'
                        back-url="{{route('admin.forms.index')}}"
                        route-prefix="admin.forms"
                        route-api-prefix="admin-api.forms"
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
