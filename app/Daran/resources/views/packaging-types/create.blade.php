@extends('daran::layouts.master')

@section('title')
    @lang('daran::item.create-family')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::item.create-family')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="pageForm" class="form-template-1" method="post" action="{{ route('admin.packaging-types.store') }}" enctype="multipart/form-data">
                @include('daran::packaging-types.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <button type="submit" id="save-publish" class="btn btn-primary">@lang('daran::common.save')</button>
                        <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent
@endsection
