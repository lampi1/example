@extends('daran::layouts.master')

@section('title')
    @lang('daran::faq.edit-faq')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::faq.edit-faq')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="faqForm" class="form-template-1" method="post" action="{{ route('admin.faqs.update',['faq'=>$faq->id]) }}" enctype="multipart/form-data">
                @include('daran::faqs.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="_method" value="put" />
                        @can('publish faq')
                            <button type="button" class="btn btn-primary" id="save-publish">
                                @if($faq->state == 'published')
                                    @lang('daran::common.update')
                                @else
                                    @lang('daran::common.publish')
                                @endif
                            </button>
                        @endcan
                        <button type="button" class="btn btn-primary" id="save-draft">@lang('daran::common.draft')</button>
                        <a target="_blank" href="{{LaravelLocalization::getLocalizedURL($faq->locale, route('faqs.view',['permalink'=>$faq->slug]))}}" class="btn btn-secondary" >@lang('daran::common.preview')</a>
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
        var url_attachment_delete = "{{ config('app.url') }}"+ "/admin-api/faq-attachment/";
        var url_img_cover_delete = "{{ config('app.url') }}"+ "/admin-api/faq-image/";
        var url_add_category = "{{ config('app.url') }}"+ "/admin-api/faq-categories/"
        urldelete = "{{ config('app.url') }}/admin-api/faqs/";
        var locale = '{{app()->getLocale()}}';

        $('#save-publish').on('click',function(){
            publishOrDraft('faqForm', true, 'published', error_attach, true);
        });
        $('#save-draft').on('click',function(){
            publishOrDraft('faqForm', true, 'draft', error_attach, true);
        });

        //img 1
        $('.input--image').on('click', '.delete',function(e){
            var faq_id = {{$faq->id}};
            $.ajax({
                url:url_img_cover_delete + faq_id,
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
