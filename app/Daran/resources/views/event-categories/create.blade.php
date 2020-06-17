@extends('daran::layouts.master')

@section('title')
    @lang('daran::event.create-event-category')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::event.create-event-category')</h3>
        </div>
        <div class="col-12 mb-3">
            <form class="form-template-1" method="post" action="{{ route('admin.event-categories.store') }}" enctype="multipart/form-data">
                @include('daran::event-categories.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="locale_group" value="{{$locale_group}}" />
                        <button type="submit" id="save-publish" class="btn btn-primary" >@lang('daran::common.save')</button>
                        <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
                    </div>
                </div>
            </form>
        </div>
@endsection

@section('footer_scripts')
    @parent

@endsection
