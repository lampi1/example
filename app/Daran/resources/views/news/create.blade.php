@extends('daran::layouts.master')

@section('title')
    @lang('daran::news.create-news')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::news.create-news')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="newsForm" class="form-template-1" method="post" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
                @include('daran::news.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="locale_group" value="{{$locale_group}}" />
                        @can('publish news')
                            <button type="button" class="btn btn-primary" id="save-publish">@lang('daran::common.publish')</button>
                        @endcan
                        <button type="button" class="btn btn-primary" id="save-draft">@lang('daran::common.draft')</button>
                        <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent
    <script type="text/javascript">
        var url_attachment_delete = "{{ config('app.url') }}"+ "/admin-api/news-attachment/";
        var url_img_cover_delete = "{{ config('app.url') }}"+ "/admin-api/news-image/";
        var url_add_category = "{{ config('app.url') }}"+ "/admin-api/news-categories/"
        var locale = '{{app()->getLocale()}}';

        $('#save-publish').on('click',function(){
            publishOrDraft('newsForm', true, 'published', error_attach, true);
        });
        $('#save-draft').on('click',function(){
            publishOrDraft('newsForm', true, 'draft', error_attach, true);
        });

        //img 1
        $('.input--image').on('click', '.delete',function(e){
            e.currentTarget.hidden = true;
            e.delegateTarget.children[2].children[0].hidden = false;
            e.delegateTarget.children[1].innerHTML= ""
        });

    </script>
@endsection
