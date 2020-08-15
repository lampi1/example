@extends('daran::layouts.master')

@section('title')
    @lang('daran::gallery.edit-gallery')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::gallery.edit-gallery')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="postForm" class="form-template-1" method="post" action="{{ route('admin.galleries.update',['gallery'=>$gallery->id]) }}" enctype="multipart/form-data">
                @include('daran::galleries.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="_method" value="put" />
                        @can('publish gallery')
                            <button type="button" class="btn btn-primary" id="save-publish">
                                @if($gallery->state == 'published')
                                    @lang('daran::common.update')
                                @else
                                    @lang('daran::common.publish')
                                @endif
                            </button>
                        @endcan
                        <button type="button" class="btn btn-primary" id="save-draft">@lang('daran::common.draft')</button>
                        <a target="_blank" href="{{LaravelLocalization::getLocalizedURL($gallery->locale, route('galleries.view',['permalink'=>$gallery->slug]))}}" class="btn btn-secondary" >@lang('daran::common.preview')</a>
                        <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>

                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-vuetable ref="vuetable"
                fields-array="GalleryMedia"
                api-url="{{route('admin-api.galleries-medias.index',['gallery_id'=>$gallery->id])}}"
                :paginate="25"
                :route-prefix="'admin.medias'"
                :route-api-prefix="'admin-api.galleries-medias'"
                :is-sortable="false"
                :parent-id="{{$gallery->id}}"
                >
            </daran-vuetable>
        </div>
        <div class="col-12 mb-3">
            <a href="{{route('admin.medias.create',['id'=>$gallery->id, 'type' => 'image'])}}" class="btn btn-primary">@lang('daran::gallery.add-media')</a>
            <a href="{{route('admin.medias.create',['id'=>$gallery->id, 'type' => 'video'])}}" class="btn btn-primary">@lang('daran::gallery.add-video-media')</a>
            <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
        </div>
    </div>

@endsection

@section('footer_scripts')
    @routes
    @parent

    <script type="text/javascript">
        var url_img_cover_delete = "{{ config('app.url') }}"+ "/admin-api/galleries-image/";
        urldelete = "{{ config('app.url') }}/admin-api/galleries/";
        var locale = '{{app()->getLocale()}}';

        $('#save-publish').on('click',function(){
            publishOrDraft('postForm', true, 'published', error_attach, true);
        });
        $('#save-draft').on('click',function(){
            publishOrDraft('postForm', true, 'draft', error_attach, true);
        });

        //img 1
        $('.input--image').on('click', '.delete',function(e){
            var gallery_id = {{$gallery->id}};
            var type = $(this).data('type');
            $.ajax({
                url:url_img_cover_delete + gallery_id+ '?type=' + type,
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
