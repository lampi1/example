@extends('daran::layouts.master')

@section('title')
    @lang('daran::gallery.edit-media')
    @parent
@endsection


@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::gallery.edit-media')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="postForm" class="form-template-1" method="post" action="{{ route('admin.medias.update',['id'=>$media->id]) }}" enctype="multipart/form-data">
                @include('daran::galleries.form-media')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="type" value="{{$media->type}}" />
                        <input type="hidden" name="gallery_id" value="{{$media->gallery_id}}" />
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="_method" value="put" />
                        <button type="submit" class="btn btn-primary" id="save-publish">@lang('daran::common.save')</button>
                        <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
                    </div>
                </div>
            </form>
        </div>

@stop

{{-- page level scripts --}}
@section('footer_scripts')
    @routes
    @parent

    <script type="text/javascript">
        var url_img_cover_delete = "{{ config('app.url') }}"+ "/admin-api/galleries/media-image/";
        urldelete = "{{ config('app.url') }}/admin-api/galleries/media/";
        var locale = '{{app()->getLocale()}}';


        //img 1
        $('.input--image').on('click', '.delete',function(e){
            var current_id = {{$media->id}};
            var type = $(this).data('type');
            $.ajax({
                url:url_img_cover_delete + current_id+ '?type=' + type,
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
