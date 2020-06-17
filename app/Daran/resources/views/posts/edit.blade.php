@extends('daran::layouts.master')

@section('title')
    @lang('daran::post.edit-post')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::post.edit-post')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="postForm" class="form-template-1" method="post" action="{{ route('admin.posts.update',['post'=>$post->id]) }}" enctype="multipart/form-data">
                @include('daran::posts.form')
                <div class="row">
                    <div class="col-6">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="_method" value="put" />
                        @can('publish post')
                            <button type="button" class="btn btn-primary" id="save-publish">
                                @if($post->state == 'published')
                                    @lang('daran::common.update')
                                @else
                                    @lang('daran::common.publish')
                                @endif
                            </button>
                        @endcan
                        <button type="button" class="btn btn-primary" id="save-draft">@lang('daran::common.draft')</button>
                        <a target="_blank" href="{{LaravelLocalization::getLocalizedURL($post->locale, route('blogs.view',['category'=>$post->category->slug,'permalink'=>$post->slug]))}}" class="btn btn-secondary" >@lang('daran::common.preview')</a>
                        <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
                    </div>
                    @if(config('daran.ecommerce.enable'))
                        <div class="col-6 text-right">
                            <a href="{{route('admin.posts.edit_related',['id'=>$post->id])}}" class="btn btn-secondary">@lang('daran::item.manage-related')</a>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @include('daran::layouts._modal_delete')
@endsection

@section('footer_scripts')
    @parent

    <script type="text/javascript">
        var url_img_cover_delete = "{{ config('app.url') }}"+ "/admin-api/post-image/";
        var url_add_category = "{{ config('app.url') }}"+ "/admin-api/post-categories/"
        urldelete = "{{ config('app.url') }}/admin-api/posts/";
        var locale = '{{app()->getLocale()}}';

        $('#save-publish').on('click',function(){
            publishOrDraft('postForm', true, 'published', error_attach, true);
        });
        $('#save-draft').on('click',function(){
            publishOrDraft('postForm', true, 'draft', error_attach, true);
        });

        //img 1
        $('.input--image').on('click', '.delete',function(e){
            var post_id = {{$post->id}};
            var type = $(this).data('type');
            $.ajax({
                url:url_img_cover_delete + post_id + '?type=' + type,
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
