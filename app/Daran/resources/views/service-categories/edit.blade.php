@extends('daran::layouts.master')

@section('title')
    @lang('daran::service.edit-service-category')
    @parent
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::service.edit-service-category')</h3>
        </div>
        <div class="col-12 mb-3">
            <form  class="form-template-1" method="post" action="{{ route('admin.service-categories.update', ['service_category' => $category]) }}" enctype="multipart/form-data">
                @include('daran::service-categories.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_method" value="put" />
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="locale_group" value="{{$locale_group}}" />
                        <button type="submit" class="btn btn-primary" id="save-publish">@lang('daran::common.save')</button>
                        <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('daran::layouts._modal_delete')
@endsection

@section('footer_scripts')
    @parent

@endsection
