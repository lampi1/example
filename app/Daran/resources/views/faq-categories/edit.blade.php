@extends('daran::layouts.master')

@section('title')
    @lang('daran::faq.edit-faq-category')
    @parent
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::faq.edit-faq-category')</h3>
        </div>
        <div class="col-12 mb-3">
            <form  class="form-template-1" method="post" action="{{ route('admin.faq-categories.update', ['id' => $faqCategory->id]) }}" enctype="multipart/form-data">
                @include('daran::faq-categories.form')
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
