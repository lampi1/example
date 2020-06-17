@extends('daran::layouts.master')

@section('title')
    @lang('daran::gallery.add-media')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::gallery.add-media')</h3>
        </div>
        <div class="col-12 mb-3">
            <form action="{{ route('admin.medias.store') }}" id="blogForm" method="post" class="form-template-1" enctype="multipart/form-data">
                @include('daran::galleries.form-media')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="type" value="{{$media->type}}" />
                        <input type="hidden" name="gallery_id" value="{{$media->gallery_id}}" />
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <button type="submit" class="btn btn-primary" id="save-publish">@lang('daran::common.save')</button>
                        <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('footer_scripts')
    @parent
    <script type="text/javascript">
        var url_img_cover_delete = "{{ config('app.url') }}"+ "/admin-api/gallery-image/";
        var locale = '{{app()->getLocale()}}';

        //img 1
        $('.input--image').on('click', '.delete',function(e){
            e.currentTarget.hidden = true;
            e.delegateTarget.children[2].children[0].hidden = false;
            e.delegateTarget.children[1].innerHTML= ""
        });

    </script>
@endsection
