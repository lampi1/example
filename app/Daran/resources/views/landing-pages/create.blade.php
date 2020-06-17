@extends('daran::layouts.master')

@section('title')
    @lang('daran::landing_page.create-page')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::landing_page.create-page')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="pageForm" class="form-template-1" method="post" action="{{ route('admin.landing-pages.store') }}" enctype="multipart/form-data">
                @include('daran::landing-pages.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="locale_group" value="{{$locale_group}}" />
                        @can('publish page')
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
        var url_attachment_delete = "{{ config('app.url') }}"+ "/admin-api/page-attachment/";
        var url_img_cover_delete = "{{ config('app.url') }}"+ "/admin-api/page-image/";
        var url_add_category = "{{ config('app.url') }}"+ "/admin-api/page-categories/"
        var locale = '{{app()->getLocale()}}';

        $('#save-publish').on('click',function(){
            publishOrDraft('pageForm', true, 'published', error_attach, true);
        });
        $('#save-draft').on('click',function(){
            publishOrDraft('pageForm', true, 'draft', error_attach, true);
        });

        //img 1
        $('.input--image').on('click', '.delete',function(e){
            e.currentTarget.hidden = true;
            e.delegateTarget.children[2].children[0].hidden = false;
            e.delegateTarget.children[1].innerHTML= ""
        });

    </script>
@endsection
