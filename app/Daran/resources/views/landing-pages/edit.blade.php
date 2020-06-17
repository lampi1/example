@extends('daran::layouts.master')

@section('title')
    @lang('daran::landing_page.edit-page')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::landing_page.edit-page')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="pageForm" class="form-template-1" method="post" action="{{ route('admin.landing-pages.update',['id'=>$landing_page->id]) }}" enctype="multipart/form-data">
                @include('daran::landing-pages.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="_method" value="put" />
                        @can('publish page')
                            <button type="button" class="btn btn-primary" id="save-publish">
                                @if($landing_page->state == 'published')
                                    @lang('daran::common.update')
                                @else
                                    @lang('daran::common.publish')
                                @endif
                            </button>
                        @endcan
                        <button type="button" class="btn btn-primary" id="save-draft">@lang('daran::common.draft')</button>
                        <a target="_blank" href="{{LaravelLocalization::getLocalizedURL($landing_page->locale, route('landings.view',['permalink'=>$landing_page->slug]))}}" class="btn btn-secondary" >@lang('daran::common.preview')</a>
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

    <script type="text/javascript">
        var url_attachment_delete = "{{ config('app.url') }}"+ "/admin-api/page-attachment/";
        var url_img_cover_delete = "{{ config('app.url') }}"+ "/admin-api/page-image/";
        var url_add_category = "{{ config('app.url') }}"+ "/admin-api/page-categories/"
        urldelete = "{{ config('app.url') }}/admin-api/pages/";
        var locale = '{{app()->getLocale()}}';

        $('#save-publish').on('click',function(){
            publishOrDraft('pageForm', true, 'published', error_attach, true);
        });
        $('#save-draft').on('click',function(){
            publishOrDraft('pageForm', true, 'draft', error_attach, true);
        });

        //img 1
        $('.input--image').on('click', '.delete',function(e){
            var type = $(this).data('type');
            var page_id = {{$landing_page->id}};
            $.ajax({
                url:url_img_cover_delete + page_id + '?type=' + type,
                type: 'post',
                data: {_method: 'put' },
                success: function (data){
                    if (data.success){
                        e.currentTarget.hidden = true;
                        e.delegateTarget.children[2].children[0].hidden = false;
                        e.delegateTarget.children[1].innerHTML= ""
                    } else {
                        bootbox.alert("@lang('daran::common.default-error')");
                    }
                },
                error: function (data){
                    bootbox.alert("@lang('daran::common.default-error')");
                }
            });
        });
        </script>
@endsection
